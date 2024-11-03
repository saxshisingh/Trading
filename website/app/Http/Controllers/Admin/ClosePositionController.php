<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Portfolio;
use App\Models\Trading;
use App\Models\Wallet;


class ClosePositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $positions = Trading::with('wallet', 'script', 'segment')
            ->where('portfolio_status', 'close')
            ->whereNotNull('position_id')
            ->get(); 

        return view('pages.Position.close', compact('positions'))
            ->with('isWelcomePage', false)
            ->with('isvideo', false);
    }

    private function calculateBrokerage($total_buy_amount, $total_sell_amount)
    {

        $brokerage_percentage = 0.1; 
        $brokerage = ($total_buy_amount + $total_sell_amount) * $brokerage_percentage / 100;
        
        return $brokerage;
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
