<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Trading;
use App\Models\Portfolio;

class DashboarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users = User::all();
        $trade = Trading::all();
        $userCount = $users->count();
        $brokerageRate = 0.001;
        $activeProfitLoss = Portfolio::where('status', 'active long')->orWhere('status','active long')->sum('m2m');

        $activeTradesCount = Trading::where('portfolio_status','open')->count();
        $buyTurnoverCount_MCX = Trading::where('action', 'BUY')
                                ->where('portfolio_status', 'close')
                                ->whereHas('segment', function ($query) {
                                    $query->where('exchange', 'MCX');
                                })
                                ->count();
        $buyTurnoverCount_NSE = Trading::where('action', 'BUY')
                                ->where('portfolio_status', 'close')
                                ->whereHas('segment', function ($query) {
                                    $query->where('exchange', 'NSE');
                                })
                                ->count();
        $sellTurnoverCount_MCX = Trading::where('action', 'SELL')
                                ->where('portfolio_status', 'open')
                                ->whereHas('segment', function ($query) {
                                    $query->where('exchange', 'MCX')
                                        ->whereNotNull('deleted_at');
                                })
                                ->count();    
        $sellTurnoverCount_NSE = Trading::where('action', 'SELL')
                                ->where('portfolio_status', 'open')
                                ->whereHas('segment', function ($query) {
                                    $query->where('exchange', 'NSE')
                                        ->whereNotNull('deleted_at');
                                })
                                ->count();     
                  
        
        $marginUsed = "0";
        $profitLoss_MCX = Trading::
            where('portfolio_status', 'close')
            ->whereHas('segment', function ($query) {
                $query->where('exchange', 'MCX')
                    ->whereNotNull('deleted_at');
            })
            ->with('wallet') 
            ->get()
            ->sum(function ($trading) {
                return $trading->wallet ? $trading->wallet->amount : 0;
            });

        $profitLoss_NSE = Trading::
            where('portfolio_status', 'close')
            ->whereHas('segment', function ($query) {
                $query->where('exchange', 'NSE')
                    ->whereNotNull('deleted_at');
            })
            ->with('wallet') 
            ->get()
            ->sum(function ($trading) {
                return $trading->wallet ? $trading->wallet->amount : 0;
            });
        $brokerage_MCX = ($buyTurnoverCount_MCX + $sellTurnoverCount_MCX) * $brokerageRate;
        $brokerage_NSE = ($buyTurnoverCount_NSE + $sellTurnoverCount_NSE) * $brokerageRate;
        return view('pages.Dashboard.dashboard', compact('userCount','activeTradesCount', 'profitLoss_MCX','profitLoss_NSE','activeProfitLoss', 'marginUsed','sellTurnoverCount_MCX','brokerage_MCX','brokerage_NSE', 'buyTurnoverCount_MCX','sellTurnoverCount_NSE','buyTurnoverCount_NSE'))->with('isWelcomePage', false)->with('isvideo', false);
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
