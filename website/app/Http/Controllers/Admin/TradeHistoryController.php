<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Trading;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TradeHistoryExport;

class TradeHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // private $kite;

    // public function __construct()
    // {
    //     $this->kite = new KiteConnect("tnpl2yjakthm5zcg");
    // }

    public function index(Request $request)
    {
        $users = User::where('role_id', '1')->get();
        $trades = Trading::with('user','segment','script','expiry')->where('portfolio_status','close')->whereNotNull('buy_at')->whereNotNull('sell_at');
        if ($request->after) {
            $startDate = \Carbon\Carbon::createFromFormat('Y-m-d', $request->after);
            $trades = $trades->where('created_at', '>=', $startDate);            
        }

        if ($request->before) {
            $endDate = \Carbon\Carbon::createFromFormat('Y-m-d', $request->before);
            $trades = $trades->whereDate('created_at', '<', $endDate);
        }
        if($request->user_id!=NULL)
        {
            $trades->where('user_id',$request->user_id);
        }
        $trades = $trades->get();
        return view('pages.History.trade_history', compact('trades', 'users'))->with('isWelcomePage', false)->with('isvideo', false);
    }

    /**
     * Show the form for creating a new resource.
     */

     public function export()
     {
         return Excel::download(new TradeHistoryExport , 'transaction_history.xlsx');
     }

    public function create(Request $request)
    {
        $filters = [
            'after' => $request->input('after'),
            'before' => $request->input('before'),
            'user_id' => $request->input('user_id'),
        ];
        return Excel::download(new TradeHistoryExport($filters) , 'trade_history.xlsx');
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
