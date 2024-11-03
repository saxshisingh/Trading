<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Script;
use App\Models\Segment;
use App\Models\ExpiryDate;
use App\Models\WatchlistLog;
use App\Models\KiteToken;
use Carbon\Carbon;

class BlockScriptController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $script = Script::where('segment_id','1')->orWhere('segment_id','6')->with('segment');
        $segment = Segment::get();
        
        $expiry = ExpiryDate::with('script.segment', 'script');
       
        if ($request->segment_id!=null) {
            $expiry->whereHas('script', function ($query) use ($request) {
                $query->where('segment_id', $request->segment_id);
            });
        }
        
        if ($request->script_id!=null) {
            $expiry->where('script_id', $request->script_id);
        }
        if ($request->is_banned!=null) {
            $expiry->whereHas('script', function ($query) use ($request) {
                $query->where('is_banned', $request->is_banned);
            });
        }

        $token = KiteToken::first();
        $token = $token->access_token;
        $expiry = $expiry->paginate(\Request::get('page_size')?\Request::get('page_size'):10);
        
        return view('pages.Blocked.index', compact('segment','expiry','token'))->with('isWelcomePage', false)->with('isvideo', false);
    }

    /**
     * Show the form for creating a new resource.
     */

     public function toggleBan(Request $request, $id)
    {
        try {
            $script = Script::where('id',$id)->first();
            if($script->is_banned=='1'){
                $script->is_banned='0';
            }else{
                $script->is_banned='1';
            }
            if($script->save()){
                return \Redirect::back()->withInput(['success' => 'Script '.($script->is_banned==0?'Activated':'Banned')]);
            }else{
                return \Redirect::back()->withInput(['error' => 'Something Went Wrong']);
            }
        } catch (\Throwable $th) {
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
        $expiryDates = ExpiryDate::all();

        // foreach ($expiryDates as $expiryDate) {
        //     $script = $expiryDate->script; 
        //     $segment = $script->segment; 
        //     $strike = $expiryDate->strike;
        //     $instrument_type = $expiryDate->instrument_type;

        //     $scriptname = str_replace(' ', '_', $script->name);
            
        //     $exchange = $segment->exchange;

        //     if($segment->instrument_type=="EQ")
        //     {
        //         $instr = $segment->name;
        //     }
        //     else{
        //         if ($exchange == "MCX") {
        //             $instr = $segment->instrument_type . 'COM';
        //         } else {
        //             $instr = $segment->instrument_type . 'STK';
        //         }
        //     }
        //     $expireDate = $expiryDate->expiry_date;
        //     $formattedDate = Carbon::parse($expireDate)->format('dMY');
        //     $formattedDate = strtoupper($formattedDate);
        //     if($instrument_type != "FUT" && $instrument_type != "EQ")
        //     {
        //         $expiryDate->stock= $exchange.'_'.$instr.'_'.$scriptname.'_'.$formattedDate.'_'.$strike.'_'.$instrument_type;       
        //     }
        //     elseif($instrument_type == "FUT"){
        //         $expiryDate->stock = $exchange . '_' . $instr . '_' . $scriptname.'_'.$formattedDate;
        //     }
        //     else{
        //         $expiryDate->stock = $exchange . '_' . $instr . '_' . $scriptname;
        //     }
        //     $expiryDate->save();
        // }

        foreach ($expiryDates as $item) {
            $stockName = $item->stock;
            $log = WatchlistLog::where('stock', $item->stock)->first();
                if(!$log){
                    $log = new WatchlistLog();
                    $log->stock = $item->stock;
                    $responseData = [
                        "UniqueName" => $item->stock,
                        "Symbol"=> $item->script->name,
                        "ExpiryString"=> $item->expiry_date,
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
                    ];
                    $log->tradingsymbol = $item->tradingsymbol;
              
                    $log->response = json_encode($responseData);
                    $log->save();
                    $item->log_id = $log->id;
                    $item->save();
                }
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
        
    }
}
