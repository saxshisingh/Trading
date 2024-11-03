<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Trade;
use App\Models\Segment;
use App\Models\Script;

class EditDeleteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    try {
        $segment = Segment::get();
        $script = Script::get();

        $query = Trade::with('user', 'script', 'segment', 'order')
            ->where(function ($query) {
                $query->where("status", "1")
                    ->orWhere("status", "2");
            })
            ->where(function ($query) {
                $query->where('is_updated', '1')
                    ->orWhereNotNull('deleted_at');
            });

        if ($request->update) {
            $query->where('is_updated', '1');
        }
        if ($request->delete) {
            $query->whereNotNull('deleted_at');
        }
        if ($request->segment_id) {
            $query->where("segment_id", $request->segment_id);
        }
        if ($request->script_id) {
            $query->where("script_id", $request->script_id);
        }
        if ($request->after) {
            $startDate = \Carbon\Carbon::createFromFormat('Y-m-d', $request->after);
            $query->where('created_at', '>=', $startDate);
        }
        if ($request->before) {
            $endDate = \Carbon\Carbon::createFromFormat('Y-m-d', $request->before);
            $query->whereDate("created_at", "<", $endDate);
        }

        $trade = $query->paginate($request->get('page_size', 10));

        return view('pages.Utilities.Edit_Delete.edit_delete', compact('trade', 'segment', 'script', 'request'))
            ->with('isWelcomePage', false)
            ->with('isvideo', false);
    } catch (\Throwable $th) {

        dd($th);
        return redirect()->back()->withInput(['error' => 'Something Went Wrong']);
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
