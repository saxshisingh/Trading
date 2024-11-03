<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Script;
use App\Models\Segment;

class BannedController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $script = Script::with('segment')->where('is_banned','1')->get();
        return view('pages.Trading.Banned.banned_blocked', compact('script'))->with('isWelcomePage', false)->with('isvideo', false);
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
