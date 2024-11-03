<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Segment;
use App\Models\Script;
use App\Models\ExpiryDate;

class MaxQuantityDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $segment = Segment::get();
        $scripts = Script::has('segment');
        if ($request->segment_id) {
            $scripts->where('segment_id', $request->segment_id);
        }    
        if ($request->script_id) {
            $scripts->where('id', $request->script_id);
        }       
        $script = $scripts->paginate(\Request::get('page_size')?\Request::get('page_size'):10);
        return view('pages.MaxQuantity.index', compact('segment','script'))->with('isWelcomePage', false)->with('isvideo', false);

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
       //
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
        $lot = Script::with('segment')->find($id);
        return view('pages.MaxQuantity.edit',compact('lot'))->with('isWelcomePage', false)->with('isvideo', false);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try{
            $log = Script::find($id);
            $log->position_limit = $request->position_limit;
            $log->max_order = $request->max_order;
            $log->save();

            if($log->save()){
                return \Redirect::route('quantity.index')->withInput(['success' => 'Max Quantity Details updated successfully']);
            }else{
                return \Redirect::route('quantity.index')->withInput(['error' => 'Something Went Wrong']);
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
