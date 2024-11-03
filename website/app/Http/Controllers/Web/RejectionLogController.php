<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Segment;
use App\Models\Script;
use App\Models\Trading;

class RejectionLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try{
            $segment = Segment::get();
            $script = Script::get();
            $query = Trading::query()->with('script', 'segment', 'user')->where('user_id', auth()->user()->id)->where('status','2');
        
            if ($request->segment_id) {
                $query->where("segment_id", $request->segment_id);
            }
            if ($request->script_id) {
                $query->where("script_id", $request->script_id);
            }
            if($request->after){
                $startDate = \Carbon\Carbon::createFromFormat('Y-m-d', $request->after);
                $query = $query->where('created_at', '>=', $startDate);            
            }
            if($request->before){
                $endDate = \Carbon\Carbon::createFromFormat('Y-m-d', $request->before);
                $query = $query->whereDate("created_at","<",$request->before);
            }

            $trade = $query->paginate($request->get('page_size', 10));
            return view('pages.Utilities.Rejection.rejection_log', compact('trade','segment','script','request'))->with('isWelcomePage', false)->with('isvideo', false);
        }catch (\Throwable $th) {
      dd($th);
        return \Redirect::back()->withInput(['error'=>'something went wrong'.$th]);
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
        //
    }
}
