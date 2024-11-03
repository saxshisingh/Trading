<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Portfolio;
use App\Models\Trading;

class ActivePositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $portfolios = Trading::with('script','segment','expiry','user','wallet')->where('portfolio_status','open')->get();

        $totals = [
            'active_buy' => $portfolios->filter(function ($portfolio) {
                return $portfolio->action === 'BUY';
            })->sum('qty'),            
            'active_sell' => $portfolios->filter(function ($portfolio) {
                return $portfolio->action === 'SELL';
            })->sum('qty'),    
            'avg_buy_rate' => $portfolios->filter(function ($portfolio) {
                return $portfolio->action === 'BUY';
            })->sum('buy_rate'),
            'avg_sell_rate' => $portfolios->filter(function ($portfolio) {
                return $portfolio->action === 'SELL';
            })->sum('sell_rate'),
            'total' => $portfolios->sum('qty'),
       
        ];

        return view('pages.Position.active', compact('portfolios','totals'))
            ->with('isWelcomePage', false)
            ->with('isvideo', false);
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
