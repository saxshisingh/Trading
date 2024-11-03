<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
use App\Lib\KiteConnect;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Http\Request;
use App\Models\Segment;
use App\Models\Notification;
use App\Models\NotificationLog;
use App\Models\Script;
use App\Models\Trading;
use App\Models\Watchlist;
use App\Models\Position;
use App\Models\User;
use App\Models\Wallet;
use App\Models\ExpiryDate;
use App\Models\KiteToken;
use App\Helpers\BalanceCheck;
use Carbon\Carbon;

class PortfolioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // private $kite;

    // public function __construct()
    // {
    //     $this->kite = new KiteConnect("tnpl2yjakthm5zcg");
    // }

    public function index(Request $request)
    {
        $segment = Segment::get();
        $query = Position::with('user', 'segment', 'script', 'expiry')->where('user_id', auth()->user()->id)->where("status","open");
        if($request->orderType!=null){
            if($request->orderType =="outstandin")
            {
                $query = $query->where('status',"1");
            }
        }
        if($request->segment_id!=null){
            $query = $query->where("segment_id",$request->segment_id);
        }
        if($request->script_id!=null){
            $query = $query->where("script_id",$request->script_id);
        }
        if($request->expiry_date_id!=null){
            $query = $query->where("expiry_date_id",$request->expiry_date_id);
        }
        $user = auth()->user();
        $balance = $user->balance;
        $position=$query->orderBy('id','DESC')->get();
        $token = KiteToken::first();
        $token = $token->access_token;
        $extreme = $user->auto_close_limit;
        $isExtreme=false;
        if($user->initial_balance > 0 && $user->balance <= ($user->initial_balance * ((100 - $extreme) / 100)))
        {
            $isExtreme = true;
        }
        return view('pages.Trading.Portfolio.portfolio_position', compact('segment', 'position','request','token','balance','isExtreme'))->with('isWelcomePage', false)->with('isvideo', false);
    }

    public function triggerCloseAll(Request $request)
    {
        $user = Auth::user();
        $thresholdAmount = $user->balance * 0.9;

        if ($user->balance < $thresholdAmount) {
            return response()->json(['trigger' => true]);
        }
        return response()->json(['trigger' => false]);
    }


    /**
     * Show the form for creating a new resource.
     */


    // private function get_position(string $id)
    // {
    //     $kiteToken = KiteToken::where('user_id', auth()->user()->id)->whereNull('deleted_at')->first();

    //     if (!$kiteToken) {
    //         return redirect()->route('trades.index')->withErrors(['error' => 'User not authenticated']);
    //     }

    //     $this->kite->setAccessToken($kiteToken->access_token);
    //     $position = $this->kite->getPositions();
    //     return $position;
    // }
    private function brokerage($position, $price, $user)
    {
        // dd($user);
        $tradeAmount = $price;
        $lotSize = $position->lot;
        if($position->segment->name=='MCX')
        {
            $brokerage = $user->charge_per_lot_mcx;
        }
        elseif($position->segment->name=='NSE')
        {
            $brokerage = $user->charge_per_lot;
        }
        $user->balance-=$brokerage;
        $user->debit+=$brokerage;
        $wallet = new Wallet();
        $wallet->user_id = $user->id;
        $wallet->category = "Brokerage Fees";
        $wallet->remarks = "Brokerage Fees";
        $wallet->amount = $brokerage;
        $wallet->profit_loss = $brokerage;
        $wallet->type = 'debit';
        $wallet->status = "success";
        $wallet->action = "brokerage";
    
        $notification = new Notification();
        $notification->title = "Brokerage Fees";
        $notification->message = $brokerage.' Brokerage Fees is deducted Successfully';
        $notification->send_at = now();
        $notification->save();

        $notificationLog = new NotificationLog();
        $notificationLog->user_id = $user->id;
        $notificationLog->notification_id = $notification->id;
        $notificationLog->save();
        // dd($user, $brokerage);
        $wallet->save();
        $user->save();
        return $brokerage;
    }

    protected function balance($user)
    {
        // dd($user);
        $notificationSent = BalanceCheck::checkAndNotify($user);
        return $notificationSent; 
    }

    private function isIntraday($openTime, $closeTime)
    {
        $openDate = Carbon::parse($openTime);
        $closeDate = Carbon::parse($closeTime);
        $oneDayInMillis = 24 * 60 * 60; 

        return $closeDate->diffInSeconds($openDate) < $oneDayInMillis;
    }
    
    public function check(Request $request)
    {
        try{
            $user = User::find(auth()->user()->id);
            // $equity_margin = $user->intraday_margin_equity;
            $before = $user->balance;
            
            if($request->has('close_position')){
                $portfoliosJson = $request->input('portfolios');
                $portfolios = json_decode($portfoliosJson, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    foreach ($portfolios as $portfolioData) {
                        $position = Position::with('segment','script','expiry')->find($portfolioData['id']);
                        $portfolio = Trading::with('user','segment','script','expiry')->where('portfolio_status', 'open')
                                            ->where('position_id', $portfolioData['id'])
                                            ->where('user_id', auth()->user()->id)
                                            ->first();
                        if ($portfolio) {
                            $w = Wallet::where('trade_id',$portfolio->id)->where('status','processing')->first();
                            $portfolio->portfolio_status = "close";
                            $position->status = "close";
                            $current = $portfolioData['currentValue'];
                            $mtm = $portfolioData['mtm'];
                            $original = $current - $mtm;
                            $wallet = new Wallet();
                            $user = User::find($portfolio->user_id);
                            if ($portfolioData['action'] === "SELL") {
                                $portfolio->buy_at = now()->format('Y-m-d H:i:s');
                                $portfolio->buy_rate = $current;
                                $portfolio->close_at = now()->format('Y-m-d H:i:s');
                                $position->close_at = now()->format('Y-m-d H:i:s');
                                $portfolio->action = "BUY";
                                $isIntraday = $this->isIntraday($portfolio->created_at, $portfolio->close_at);
                                $tradeType = $isIntraday ? 'intraday' : 'holding';
                                $portfolio->trade_type = $tradeType;
                                $w->status = "success";
                                $w->profit_loss = $mtm;
                                $wallet->category = "BUY ".$portfolio->script->name.$portfolio->expiry->expiry_date;
                                $wallet->remarks = "BUY ".$portfolio->script->name.$portfolio->expiry->expiry_date." Successfully";
                                $wallet->user_id = auth()->user()->id;
                                $wallet->trade_id = $portfolio->id;
                                $wallet->action = "trade";
                                $wallet->status = "success";
                                $wallet->amount = $current*$portfolio->qty; 

                                $user->balance += $mtm;
                                if($mtm<0)
                                {
                                    $wallet->type = "debit";
                                    $wallet->profit_loss = 0-$mtm; 
                                    $user->debit += $mtm;
                                }
                                else{
                                    $wallet->type = "credit";
                                    $wallet->profit_loss = $mtm; 
                                    $user->credit += $mtm;
                                }
                                $wallet->save();
                                $user->save();
                              
                                
                            } elseif ($portfolioData['action'] === "BUY") {
                                $portfolio->sell_at = now()->format('Y-m-d H:i:s');
                                $portfolio->sell_rate = $current;
                                $portfolio->close_at = now()->format('Y-m-d H:i:s');
                                $position->close_at = now()->format('Y-m-d H:i:s');
                                $isIntraday = $this->isIntraday($portfolio->created_at, $portfolio->close_at);
                                $tradeType = $isIntraday ? 'intraday' : 'holding';
                                $portfolio->trade_type = $tradeType;
                                $portfolio->action = "SELL";

                                $w->status = "success";
                                $w->profit_loss = $mtm;
                               
                                $wallet->category = "SELL ".$portfolio->script->name.$portfolio->expiry->expiry_date;
                                $wallet->remarks = "SELL ".$portfolio->script->name.$portfolio->expiry->expiry_date." Successfully";
                                $wallet->user_id = auth()->user()->id;
                                $wallet->trade_id = $portfolio->id;
                                $wallet->action = "trade";
                                $wallet->amount = $current*$portfolio->qty; 
                                $wallet->type = "credit";
                                $wallet->status = "success";

                                $user->balance += $mtm;
                                if($mtm<0)
                                {
                                    $wallet->type = "debit";
                                    $wallet->profit_loss = 0-$mtm; 
                                    $user->debit += $mtm;
                                }
                                else{
                                    $wallet->type = "credit";
                                    $wallet->profit_loss = $mtm; 
                                    $user->credit += $mtm;
                                }
                                $wallet->save();
                                $user->save();
                            }
            
                            $w->save();
                            $this->brokerage($portfolio, $mtm, $user);
                            $position->save();
                            $portfolio->save();
                        }
                    }
                    $this->balance($user);
                    $user->position_balance = $user->balance;
                    $user->save();
                    return redirect()->route('portfolio.index')->with('success', 'All active positions closed successfully.');
                } else {
                    return redirect()->route('portfolio.index')->with('error', 'No active positions found to close.');
                }
            }
            elseif($request->has('close_single_position'))
            {
                $position = Position::with('segment','script','expiry')->find($request->position_id);
             
                $portfolio = Trading::where('position_id',$position->id)->first();
             
                $position->status = "close";
                $portfolio->portfolio_status = "close";
                $w = Wallet::where('trade_id',$portfolio->id)->where('status','processing')->first();
                $trade = new Trading();
                $mtm = $request->live_mtm;
                // dd($position->price*$portfolio->qty);
                // dd($portfolio);
                    if($portfolio->action=="SELL")
                        {
                            $portfolio->buy_at = now()->format('Y-m-d H:i:s');
                            $portfolio->close_at = now()->format('Y-m-d H:i:s');
                            $position->close_at = now()->format('Y-m-d H:i:s');
                            $position->status = 'close';
                            $isIntraday = $this->isIntraday($position->created_at, $portfolio->close_at);
                            $tradeType = $isIntraday ? 'intraday' : 'holding';
                            $portfolio->trade_type = $tradeType;
                            $portfolio->buy_rate = $request->current_price;
                            $portfolio->action = "BUY";
                            $wallet = new Wallet();
                            $user = User::find($portfolio->user_id);
                            $w->status = "success";
                            $w->profit_loss = $mtm;
                            $wallet->user_id = $portfolio->user_id;
                            $wallet->trade_id = $portfolio->id;
                            $wallet->category = "BUY ".$portfolio->script->name.$portfolio->expiry->expiry_date;
                            $wallet->remarks = "BUY ".$portfolio->script->name.$portfolio->expiry->expiry_date." Successfully";
                            $wallet->action = "trade";
                            $wallet->amount = $request->current_price*$portfolio->qty; 
                            $wallet->status = "success";
                            $user->balance += $mtm;
                            if($mtm<0)
                            {
                                $wallet->profit_loss = 0-$mtm;
                                $wallet->type = "debit";
                                $user->debit += $mtm;
                            }
                            else{
                                $wallet->profit_loss = $mtm;
                                $wallet->type = "credit";
                                $user->credit += $mtm;
                            }
                            $user->save();
                            // dd($user);
                            $wallet->save();
                        }
                        elseif($portfolio->action=="BUY")
                        {
                            $portfolio->sell_at = now()->format('Y-m-d H:i:s');
                            $portfolio->close_at = now()->format('Y-m-d H:i:s');
                            $position->close_at = now()->format('Y-m-d H:i:s');
                            $position->status = 'close';
                            $isIntraday = $this->isIntraday($portfolio->created_at, $portfolio->close_at);
                            $tradeType = $isIntraday ? 'intraday' : 'holding';
                            $portfolio->trade_type = $tradeType;
                            $portfolio->sell_rate = $request->current_price;
                            $portfolio->action = "SELL";

                            $wallet = new Wallet();
                            $user = User::find($portfolio->user_id);
                            $w->status = "success";
                            $w->profit_loss = $mtm;
                            $wallet->user_id = $portfolio->user_id;
                            $wallet->trade_id = $portfolio->id;
                            $wallet->category = "SELL ".$portfolio->script->name.$portfolio->expiry->expiry_date;
                            $wallet->remarks = "SELL ".$portfolio->script->name.$portfolio->expiry->expiry_date." Successfully";
                            $wallet->action = "trade";
                            $wallet->status = "success";
                            $wallet->amount = $request->current_price*$portfolio->qty; 
                            $user->balance += $mtm;
                            if($mtm<0)
                            {
                                $wallet->profit_loss = 0-$mtm;
                                $wallet->type = "debit";
                                $user->debit += $mtm;
                            }
                            else{
                                $wallet->profit_loss = $mtm;
                                $wallet->type = "credit";
                                $user->credit += $mtm;
                            }
                            // dd($user);
                            $user->save();
                            $wallet->save();
                        }
                        // dd($user, $wallet, $mtm);
                $w->save();
                $this->brokerage($portfolio, $mtm, $user);
                $portfolio->save();
                $position->save();
                // dd($user);
                $this->balance($user);
                $user->position_balance=$user->balance; 
                $user->save();
                if($portfolio->save())
                {
                    return redirect()->route('portfolio.index')->with('success', 'Positions closed successfully.');
                }
                else{
                    return redirect()->route('portfolio.index')->with('error', 'No active positions found to close.');
                }
            }
            else{
                return redirect()->route('portfolio.index')->withInput(['error'=>'Under Maintainence.']);
            }
    }catch (\Throwable $th) {
        dd($th);
        return \Redirect::back()->withInput(['error' => 'Something Went Wrong']);
    }
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            if($request->action=="SELL")
            {
                $portfolio = Position::where('instrument_token', $request->token)
                ->where('status', 'open')
                ->where('type', "BUY")
                ->first();
            }
            elseif($request->action=="BUY")
            {
                $portfolio = Position::where('instrument_token', $request->token)
                ->where('status', 'open')
                ->where('type', "SELL")
                ->first();
            }
            
            if ($portfolio) {
                return redirect()->route('watchlist.index')->withErrors(['error' => 'Cannot '.$request->action.' '.$portfolio->script->name.$portfolio->expiry->expiry_date]);
            }
            // $user = auth()->user();
            // if($request->)
            $code = $request->exchange_code;
            
            if($code){
                $user = auth()->user();
                $time = now();
                $data = Watchlist::where('user_id', $user->id)->whereHas('expiry', function($query) use ($request) {
                    $query->where('token', $request->token);
                })->first();
                
                $segment = Segment::find($data->segment_id);
                $script = Script::find($data->script_id);
                $expiry = ExpiryDate::find($data->expiry_date_id);
                $instrument = $segment->instrument_type;
                if($segment->name=='NSE')
                {
                    $margin = $user->intraday_margin_equity;
                }
                elseif($segment->name=='MCX')
                {
                    $margin = $user->intraday_margin_mcx;
                }
                $position_balance = $user->position_balance;
                // dd($position_balance);
                if($margin*$position_balance<$request->price*$request->quantity)
                {
                    // dd($user);
                    return redirect()->route('watchlist.index')->withErrors(['error' => 'Your Margin limit exceeds']);
                }
                
                if ($segment->exchange == "NFO" && Carbon::now()->greaterThan(Carbon::today()->setTime(15, 28)))
                {
                    return redirect()->route('watchlist.index')->withErrors(['error' => 'Market Is Closed']);
                }
                $position = new Position();
                $position->user_id = auth()->user()->id;
                $position->segment_id = $segment->id;
                $position->script_id = $script->id;
                $position->expiry_date_id = $expiry->id;
                $position->type = $request->action;
                $position->tradingSymbol = $request->stock_code;
                $position->exchange = $segment->exchange;
                $position->instrument_token = $request->token;
                $position->product = "regular";
                $position->price = $request->price ;
                $position->quantity = $request->quantity;
                $position->status = "open";
                $position->lot = $request->lot;
                $trade = new Trading();
                $trade->user_id = auth()->user()->id;
                $trade->segment_id = $segment->id;
                $trade->script_id = $script->id;
                $trade->expiry_date_id = $expiry->id;
                $trade->type = $request->order_type;
                $trade->order_id = strtotime(date("Y-m-d H:i:s"));
                $trade->lot = $request->lot;
                $trade->qty = $request->quantity;
                $trade->portfolio_status ="open";
                if($request->action=="BUY")
                {
                    // if(($user->balance)<$request->price)
                    // {
                    //     return redirect()->route('watchlist.index')->withErrors(['error' => 'Insufficient Balance. Margin Limit Exceeds']);
                    // }
                    $trade->buy_rate = $request->price;
                    $trade->buy_at = now()->format('Y-m-d H:i:s');
                
                }
                else{
                    // if(($user->balance)<$request->price)
                    // {
                    //     return redirect()->route('watchlist.index')->withErrors(['error' => 'Insufficient Balance. Margin Limit Exceeds']);
                    // }
                    $trade->sell_rate = $request->price;
                    $trade->sell_at = now()->format('Y-m-d H:i:s');
                
                }
                // dd($user->position_balance);
                $user->position_balance-=($request->price*$request->quantity)/$margin;
                // dd($request->price, $request->quantity,($request->price*$request->quantity)/$margin,$user);
                $user->save();
                $position->save();
                $trade->status = '1';
                $trade->action = $request->action;
                $trade->position_id = $position->id;
                $trade->save();
                $wallet = new Wallet();
                $wallet->user_id = auth()->user()->id;
                $wallet->category = $request->action." ".$script->name;
                $wallet->trade_id = $trade->id;
                $wallet->remarks = $request->action." ".$request->script." Successfully";
                $wallet->amount = $request->price*$request->quantity;
                $wallet->type = "debit";
                $wallet->status = "processing";
                $wallet->action = "market";
                // dd($position, $wallet, $trade);
                    $wallet->save();
                }
                
            return redirect()->route('watchlist.index')->with('success', 'Successfully '.$request->action.' '.$script->name.$expiry->expiry_date);
        }
        catch(\Throwable $th){
            dd($th);
            return redirect()->route('watchlist.index')->withErrors(['error' => 'Something went wrong: ' . $th->getMessage()]);
        }
     
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $find = Trading::find($id);
            if($find)
                {
                    $find->delete();
                    return \Redirect::back()->withInput(['success' => "Successfully Deleted"]);
                }else{
                    return \Redirect::back()->withInput(['error' => "Oops Invalid Request Please try again"]);
                }
            }catch (\Throwable $th) {
       
                return \Redirect::back()->withInput(['error' => "Oops Something want wrong"]);
            }
    }
}
