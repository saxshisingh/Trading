<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Segment;
use App\Models\Script;
use App\Models\ExpiryDate;

class DetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       
        $segment = Segment::get();
        $scripts = Script::has('segment');
        $script = $scripts->paginate(\Request::get('page_size')?\Request::get('page_size'):10);
        return view('pages.Trading.Details.max_quantity_details', compact( 'segment','script'))->with('isWelcomePage', false)->with('isvideo', false);

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
