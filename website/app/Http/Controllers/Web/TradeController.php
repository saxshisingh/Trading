<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Lib\KiteConnect;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Http\Request;
use App\Models\Segment;
use App\Models\Script;
use App\Models\Trading;
use App\Models\TradeData;
use App\Models\ExpiryDate;
use App\Models\Watchlist;
use App\Models\Wallet;
use App\Models\Order;
use Carbon\Carbon;

class TradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // private $kite;
    // public function __construct()
    // {
    //     $this->kite = new KiteConnect("tnpl2yjakthm5zcg");
    // }

    // public function login()
    // {
     
    //     $login_url = $this->kite->getLoginURL();
    //     return redirect($login_url);
    // }

    // public function handleCallback(Request $request)
    // {
    //     try {
    //         $data = $this->kite->generateSession('IwjdTQtu476f1aw8nhoiSnWLk8ctjHr1', '347sf6e3feevon9a1xgyiyw9wcqk5u0p');

    //         KiteToken::where('user_id', $userId)->delete();
    //         $kite_token = new KiteToken();
    //         $kite_token->user_id = auth()->user()->id;
    //         $kite_token->access_token = $data->access_token;
    //         $kite_token->save();

    //         return redirect()->route('watchlist.index')->with('success', 'Authentication successful');
    //     } catch (Exception $e) {
    //         return redirect()->route('watchlist.index')->withErrors(['error' => 'Authentication failed: ' . $e->getMessage()]);
    //     }
    // }

    public function index(Request $request)
    {
        try {
            $segment = Segment::get();
           
            $order = Order::get();
            $trade = Trading::query()
                ->with('script', 'segment', 'user')
                ->whereDate('created_at', date("Y-m-d"))
                ->where('user_id', auth()->user()->id)
                ->where('portfolio_status','close');
                ;

            if ($request->pending) {
                $trade = $trade->where('status', '1')->orWhere('status','0');
            } elseif ($request->executes) {
                $trade = $trade->whereNotNull('close_at');
            }

            if ($request->after) {
                $startDate = \Carbon\Carbon::createFromFormat('Y-m-d', $request->after);
                $trade = $trade->where('created_at', '>=', $startDate);            
            }

            if ($request->before) {
                $endDate = \Carbon\Carbon::createFromFormat('Y-m-d', $request->before);
                $trade = $trade->whereDate('created_at', '<', $endDate);
            }

            if ($request->segment_id) {
                $trade = $trade->where('segment_id', $request->segment_id);
            }

            if ($request->script_id) {
                $trade = $trade->where('script_id', $request->script_id);
            }

            if ($request->action) {
                $trade = $trade->where('action', $request->action);
            }

           
            $trade = $trade->get();
            
            return view('pages.Trading.Trades.trades', compact('segment', 'order', 'trade'))
                ->with('isWelcomePage', false)
                ->with('isvideo', false);
        } catch (\Throwable $th) {
            dd($th);
            return \Redirect::back()->withInput(['error' => 'something went wrong: ' . $th->getMessage()]);
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    // public function instruments()
    // {
    //     $inst = $this->kite->getInstruments();
    //     dd($inst);
    // }
    
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function placingOrder(Request $request)
    // {
    //     $kiteToken = KiteToken::where('user_id', auth()->user()->id)->first();       

    //     if (!$kiteToken) {
    //         return redirect()->route('trades.index')->withErrors(['error' => 'User not authenticated']);
    //     }

    //     $this->kite->setAccessToken($kiteToken->access_token);
        
    //     print_r($this->kite->getPositions());

        
    // }

    public function store(Request $request)
    {
        try {
            $user = auth()->user();
            $time = now();
            $data = Watchlist::whereHas('expiry', function($query) use ($request) {
                $query->where('token', $request->token);
            })->first();
            
            dd($request->all());
            $segment = Segment::find($data->segment_id);
            $script = Script::find($data->script_id);
            $expiry = ExpiryDate::find($data->expiry_date_id);
        
            if ($segment->exchange == "NFO" && Carbon::now()->greaterThan(Carbon::today()->setTime(15, 28)))
            {
                return redirect()->route('watchlist.index')->withErrors(['error' => 'Market Is Closed']);
            }
            
        
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
            // dd($trade);
            if($request->action=="BUY")
            {
                if($user->balance<$request->price)
                {
                    return redirect()->route('watchlist.index')->withErrors(['error' => 'Insufficient Balance. Margin Limit Exceeds']);
                }
                $trade->buy_rate = $request->price;
                $trade->buy_at = now()->format('Y-m-d H:i:s');
               
            }
            else{
                if($user->balance<$request->price)
                {
                    return redirect()->route('watchlist.index')->withErrors(['error' => 'Insufficient Balance. Margin Limit Exceeds']);
                }
                $trade->sell_rate = $request->price;
                $trade->sell_at = now()->format('Y-m-d H:i:s');
              
            }
            $trade->status = '1';
            $trade->action = $request->action;
            $user->debit = $request->price*$request->quantity;
            if($user->balance - $request->price*$request->quantity<'0')
            {
                return redirect()->route('watchlist.index')->withErrors(['error' => 'Insufficient Balance. Margin Limit Exceeds']);
            }else{
                $user->balance = $user->balance - $request->price*$request->quantity;
            }
            $trade->save();
            
            $wallet = new Wallet();
            $wallet->user_id = auth()->user()->id;
            $wallet->category = $request->action." ".$request->script;
            $wallet->trade_id = $trade->id;
            $wallet->remarks = $request->action." ".$request->script." Successfully";
            $wallet->amount = $request->price*$request->quantity;
            $wallet->type = "debit";
            $wallet->save();
            $user->save();

            $trading_list =[];
            foreach ($trading_list as $orderData) {
                $existingTrade = TradeData::where('trade_id', $orderData->trade_id)->where('order_id',$orderData->trade_id)->first();

                if (!$existingTrade) {
                    $tradeData = new TradeData();
                }else{
                    $tradeData = $existingTrade;
                }
                $tradeData->trade_id = $orderData->trade_id;
                $tradeData->order_id = $orderData->order_id;
                $tradeData->exchange = $orderData->exchange;
                $tradeData->tradingsymbol = $orderData->tradingsymbol;
                $tradeData->instrument_token = $orderData->instrument_token;
                $tradeData->product = $orderData->product;
                $tradeData->average_price = $orderData->average_price;
                $tradeData->quantity = $orderData->quantity;
                $tradeData->exchange_order_id = $orderData->exchange_order_id;
                $tradeData->transaction_type = $orderData->transaction_type;
                $tradeData->fill_timestamp = $orderData->fill_timestamp;
                $tradeData->order_timestamp = $orderData->order_timestamp;
                $tradeData->exchange_timestamp = $orderData->exchange_timestamp;
                $tradeData->save();
            }

            // $ordersData = $this->kite->getOrders();
            $ordersData =[];
            foreach ($ordersData as $orderData) {
                $existingOrder = Order::where('order_id', $orderData->order_id)->first();

                if (!$existingOrder) {
                    $order = new Order();
                    $order->placed_by = $orderData->placed_by;
                    $order->order_id = $orderData->order_id;
                    $order->exchange_order_id = $orderData->exchange_order_id;
                    $order->parent_order_id = $orderData->parent_order_id;
                    $order->status = $orderData->status;
                    $order->status_message = $orderData->status_message;
                    $order->status_message_raw = $orderData->status_message_raw;
                    $order->order_timestamp = date_format($orderData->order_timestamp,"Y-m-d H:i:s");
                    if($orderData->exchange_update_timestamp){
                        $order->exchange_update_timestamp = date_format($orderData->exchange_update_timestamp,"Y-m-d H:i:s");
                        $order->exchange_timestamp = date_format($orderData->exchange_timestamp,"Y-m-d H:i:s");
                    }
                    $order->variety = $orderData->variety;
                    $order->modified = $orderData->modified;
                    $order->exchange = $orderData->exchange;
                    $order->tradingsymbol = $orderData->tradingsymbol;
                    $order->instrument_token = $orderData->instrument_token;
                    $order->order_type = $orderData->order_type;
                    $order->transaction_type = $orderData->transaction_type;
                    $order->validity = $orderData->validity;
                    $order->product = $orderData->product;
                    $order->quantity = $orderData->quantity;
                    $order->disclosed_quantity = $orderData->disclosed_quantity;
                    $order->price = $orderData->price;
                    $order->trigger_price = $orderData->trigger_price;
                    $order->average_price = $orderData->average_price;
                    $order->filled_quantity = $orderData->filled_quantity;
                    $order->pending_quantity = $orderData->pending_quantity;
                    $order->cancelled_quantity = $orderData->cancelled_quantity;
                    $order->market_protection = $orderData->market_protection;
                    $order->meta = json_encode($orderData->meta);
                    $order->tag = $orderData->tag;
                    $order->guid = $orderData->guid;
                    $order->save();
                }
            }
           
            return redirect()->route('watchlist.index')->withInput(['success' => 'Order Placed Successfully']);
            
        } catch (\Throwable $th) {
         
            \Log::error('Error processing request: ', ['error' => $th->getMessage()]);
            if($th->getMessage()=="Markets are closed right now. Try placing an after market order (AMO). [Read more.](https://support.zerodha.com/category/trading-and-markets/kite-web-and-mobile/articles/what-is-amo-and-when-can-we-place-it)")
                {
                    return redirect()->route('watchlist.index')->withErrors(['error' => 'Market is Closed']);
                }
            elseif($th->getMessage()=="Intraday orders (MIS) are allowed only till 3.20 PM. Try placing a CNC order.")
            {
                    return redirect()->route('watchlist.index')->withErrors(['error' => 'Intraday orders (MIS) are allowed only till 3.20 PM. Market is Closed']);
            }
            return redirect()->route('watchlist.index')->withErrors(['error' => 'Something went wrong: ' . $th->getMessage()]);
        }
    }

    function convertDateFormat($inputDate) {
        $day = substr($inputDate, 0, 2);
        $month = substr($inputDate, 2, 3);
        $year = substr($inputDate, 5, 4);
    
        $monthMap = [
            'JAN' => '01', 'FEB' => '02', 'MAR' => '03', 'APR' => '04', 
            'MAY' => '05', 'JUN' => '06', 'JUL' => '07', 'AUG' => '08', 
            'SEP' => '09', 'OCT' => '10', 'NOV' => '11', 'DEC' => '12'
        ];
    
        $numericMonth = $monthMap[strtoupper($month)];
        return "{$year}-{$numericMonth}-{$day}";
    }

    // public function trade_list()
    // {
    //     $kiteToken = KiteToken::whereNull('deleted_at')->first();
    //         if (!$kiteToken) {
    //             return redirect()->route('trades.index')->withErrors(['error' => 'User not authenticated']);
    //         }
    //         $this->kite->setAccessToken($kiteToken->access_token);
    //     $list = $this->kite->getTrades();
    //     return $list;
       
    // }

    // public function trade_by_order(string $id)
	// {
    //     $kiteToken = KiteToken::where('user_id', auth()->user()->id)->whereNull('deleted_at')->first();

    //         if (!$kiteToken) {
    //             return redirect()->route('trades.index')->withErrors(['error' => 'User not authenticated']);
    //         }
    //     $this->kite->setAccessToken($kiteToken->access_token);
	// 	$trade = $this->kite->getOrderTrades($id);
      
	// }

    // public function order_list()
    // {
    //     $kiteToken = KiteToken::where('user_id', auth()->user()->id)->whereNull('deleted_at')->first();

    //         if (!$kiteToken) {
    //             return redirect()->route('trades.index')->withErrors(['error' => 'User not authenticated']);
    //         }
    //     $this->kite->setAccessToken($kiteToken->access_token);
    //     // dd($this->kite->getQuote('NSE:INFY'));
    //     $order = $this->kite->getOrders();
        
    // }

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
        $trade = Trading::find($id);
        $trade->is_updated = '1';
        $trade->save();
        return \Redirect::route('trades.index')->withInput(['success' => 'Successfully Updated']);
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
