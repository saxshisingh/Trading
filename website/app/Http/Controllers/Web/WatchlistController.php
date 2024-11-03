<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use App\Models\Watchlist;
use App\Models\User;
use App\Models\Segment;
use App\Models\Script;
use App\Models\ExpiryDate;
use App\Models\Strike;
use App\Models\WatchlistLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use AngelBroking\AngelBroking;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client as GuzzleClient;
use App\Models\KiteToken;

class WatchlistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {  
        try{
            $user = auth()->user();
            $segment = Segment::where('name','NSE');
            if($user->equity_trading=='on' && $user->mcx_trade=='on')
            {
                $segment = Segment::get();
            }
            elseif($user->equity_trading=='on'){
                $segment = Segment::where('instrument_type','OPTSTK')->orWhere('instrument_type','OPTIDX')->orWhere('instrument_type','OPTFUT')->get();
            }
            elseif($user->mcx_trade=='on'){
                $segment = Segment::where('name','MCX')->get();
            }
            else{
                $segment = Segment::where('id', '!=', 1)->get();
            }
            $csvFilePath = storage_path('app/instruments.csv');
            $watchlist = Watchlist::with('segment','script','expiry','logs')->where('user_id',auth()->user()->id)->get()->groupBy('segment_id');
            $token = KiteToken::first();
            $token = $token->access_token;
            return view('pages.Trading.watchlist.watchlist', compact("watchlist","segment",'request','token'))->with('isWelcomePage', false)->with('isvideo', true);
        }catch (\Throwable $th) {
        return \Redirect::back()->withInput(['error'=>'something went wrong'.$th]);
    }
    }

    public function fetchScript(Request $request)
    {
        $data['script'] = Script::where('segment_id', $request->segment_id)->orderBy('name','ASC')->get(["name", "id"]);
        return response()->json($data);
    }

    public function fetchStrike(Request $request)
    {
        $strikeData = ExpiryDate::where('script_id',$request->script_id)->where('expiry_date',$request->expiry_date)->get(); 
        $strikes=[];
        foreach ($strikeData as $str) {
            if (preg_match('/(\d+)(CE|PE)$/', $str->tradingsymbol, $matches)) {
                $strikes[] = $str->strike;
            }
        }
    
        $strikes = array_unique($strikes);
        $data['strike'] = $strikes;
        return response()->json($data);
    }


    public function fetchExpiryDate(Request $request)
    {
        $data['date'] = ExpiryDate::where('script_id', $request->script_id)
                             ->groupBy('expiry_date')
                             ->get(["expiry_date"])
                             ->map(function ($expiry) {
                                 return [
                                     'expiry_date' => Carbon::parse($expiry->expiry_date)->format('dMY'),
                                 ];
                             });
        return response()->json($data);
    }

    public function fetch_log(Request $request)
    {
        $latestLog = WatchlistLog::where('token', $request->symbolToken)->first();
        if (!$latestLog) {
            return response()->json(['error' => 'Watchlist log not found'], 404);
        }
        return response()->json($latestLog);
    }

    public function fetchLotSize(Request $request)
    {
        
        $symbolToken = $request->input('symbolToken');
        $expiryDate = ExpiryDate::where('token', $symbolToken)->first();
        if ($expiryDate) {
            return response()->json(['lot_size' => $expiryDate->lot_size,'symbolToken'=>$symbolToken]);
        } else {
            return response()->json(['error' => 'Lot size not found'], 404);
        }
    }
    /**
     * Show the form for creating a new resource.
     */
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
            $validator = \Validator::make($request->all(), [
                'segment_id' => 'required',
                'script_id' => 'required',
                'expiry_date' => 'required',
                
             ]);
             
             if ($validator->fails()) { 
                return \Redirect::back()->withInput(['error' => $validator->errors()->first()]); 
            }
            $user = auth()->user();

            if($request->strike==null){
                $strike = '0';
            }
            else{
                $strike = $request->strike;
            }
            $instrument_type = $request->instrument_type;
         
            $watchlist = Watchlist::with('user','script','segment','expiry','logs')->where('user_id', auth()->user()->id)->where("segment_id", $request->segment_id)->where("script_id", $request->script_id)->where("expiry_date_id", $request->expiry_date_id)->first();
            if(!$watchlist){
                $watchlist = new Watchlist();
                $watchlist->user_id = auth()->user()->id;
                $watchlist->segment_id = $request->segment_id;
                $watchlist->script_id = $request->script_id;
                
                $scriptname = $watchlist->script->name;
                $expir = ExpiryDate::where('script_id', $request->script_id)->where('expiry_date',$request->expiry_date)->get();
                
                if($request->strike)
                {
                    $expir = $expir->where('strike',floatval($request->strike));
                }
                if($request->instrument_type)
                {
                    foreach ($expir as $str) {
                        $instrumentType = $request->instrument_type;
                        $pattern = '/(\d+)' . ($instrumentType === 'CE' ? 'CE$' : 'PE$') . '/';
                        if (preg_match($pattern, $str->tradingsymbol, $matches)) {
                            $expirydate = $str;
                        }
                    }
                }
                // dd($expirydate, "we got");
                // dd($request->all(),$expir);
                $expirydate = $expir->first();
                $watchlist->expiry_date_id = $expirydate->id; 
                $expiredate = $watchlist->expiry->expiry_date;
                $watchlist->strike = $watchlist->expiry->strike;
                $token = $watchlist->expiry->token;
                $watchlist->token = $token;
                $watchlist->instrument_type = $watchlist->expiry->instrument_type;
                
                $exchange = $watchlist->segment->exchange;
                if($exchange=="MCX")
                {
                    $instr = $watchlist->segment->instrument_type.'COM';
                }
                else{
                    $instr = $watchlist->segment->instrument_type.'STK';
                }
                $formattedDate = Carbon::parse($expiredate)->format('dMY'); 
                $formatedDate = strtoupper($formattedDate);
                
                $exp = ExpiryDate::find($expirydate->id);
                
                $exp->save();
                $log = WatchlistLog::where('token', $watchlist->token)->first();
                if($log){
                    $watchlist->logs_id = $log->id;
                }
                else{
                    $log = new WatchlistLog();
                    $log->token = $watchlist->expiry->token;
                    $responseData = [
                        "UniqueName" => $watchlist->expiry->name,
                        "Symbol"=> $scriptname,
                        "ExpiryString"=> $expiredate,
                        "LTD"=> 0,
                        "LTT"=> 0,
                        "BBP"=> 0, 
                        "BBQ"=> 0,
                        "BSP"=> 0,
                        "BSQ"=> 0,
                        "LTP"=> 0,
                        "Open"=> 0,
                        "High"=> 0,
                        "Low"=> 0,
                        "Vol"=> 0,
                        "PrevClose"=> 0,
                        "symbolToken" => $token,
                    ];
                    $log->tradingsymbol = $expirydate->tradingsymbol;
                    $log->response = json_encode($responseData);
                    $log->save();
                    // dd($log);
                }
                $watchlist->logs_id = $log->id;
                // dd($watchlist);
                $watchlist->save();
             
                return \Redirect::back()->withInput(['success'=>'Successfully added Market']);
             }
             else{                
                return \Redirect::back()->withInput(['error'=>'Data already in table']);
             }
        }
        catch (\Throwable $th) {
            dd($th);
        return \Redirect::back()->withInput(['error'=>'something went wrong'.$th]);
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
        $find = Watchlist::find($id);
        if($find)
            {
                $find->delete();
                return \Redirect::back()->withInput(['success' => "Successfully Deleted"]);
            }else{
                return \Redirect::back()->withInput(['error' => "Oops Invalid Request Please try again"]);
            }
        }catch (\Throwable $th) {
            dd($th);
            return \Redirect::back()->withInput(['error' => "Oops Something want wrong"]);
        }
    }
}
