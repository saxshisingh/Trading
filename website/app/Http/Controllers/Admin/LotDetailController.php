<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LotDetail;
use App\Models\Segment;
use App\Models\Script;
use App\Models\ExpiryDate;
use Illuminate\Support\Facades\DB;

class LotDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $lotDetails = LotDetail::with('segment', 'script');
        $segment = Segment::get();
        $script = Script::get();
        $expiry = ExpiryDate::get();
        if ($request->segment_id) {
            $lotDetails->where('segment_id', $request->segment_id);
        }
    
        if ($request->script_id) {
            $lotDetails->where('script_id', $request->script_id);
        }
    
        
        $lotDetails = $lotDetails->paginate(\Request::get('page_size')?\Request::get('page_size'):10);
        return view('pages.LotDetails.index', compact('lotDetails', 'segment','script'))->with('isWelcomePage', false)->with('isvideo', false);

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
        $segmentIds = Segment::pluck('id');
        $scriptIds = Script::pluck('id');
        $scripts = Script::with('segment')->get();

        foreach ($scripts as $script) {
            $segment = $script->segment;
            
            if ($segment) {
                $lot = new LotDetail();
                $lot->segment_id = $segment->id;
                $lot->script_id = $script->id;
                $lot->lot_quantity = '100';
                $lot->max_order = '0';
                $lot->position_limit = '0';
                $lot->save();
            }
        }


    return redirect()->back()->with('success', 'Lot details created successfully.');
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
        $lot = LotDetail::find($id);
        return view('pages.LotDetails.create',compact('lot'))->with('isWelcomePage', false)->with('isvideo', false);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try{
            $log = LotDetail::find($id);
            $log->lot_quantity = $request->lot_quantity;
            $log->save();

            if($log->save()){
                return \Redirect::route('lotdetail.index')->withInput(['success' => 'Lot Quantity updated successfully']);
            }else{
                return \Redirect::route('lotdetail.index')->withInput(['error' => 'Something Went Wrong']);
            }
        }catch (\Throwable $th) {
            dd($th);
            return \Redirect::back()->withInput(['error' => 'Something Went Wrong']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
