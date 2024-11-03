<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Trading;
use App\Models\Segment;
use App\Models\Script;
use App\Models\ExpiryDate;
use Carbon\Carbon;
use App\Models\Portfolio;

class ClosedTradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        
        $organizedTrades = Trading::with('segment','script','wallet','user')
        ->whereNotNull('position_id')
                        ->where('portfolio_status', 'close');
        $segment = Segment::all();
     
        if ($request->has('after')) {
            $afterDate = $request->after;
            $organizedTrades->whereDate('created_at', '>=', $afterDate);
        }
    
        if ($request->has('before')) {
            $beforeDate = $request->before;
            $organizedTrades->whereDate('created_at', '<=', $beforeDate);
        }

        if($request->has('segment_id'))
        {
            $seg = Segment::find($request->segment_id);
            if($seg->exchange=='NFO')
            {
                $exchange = 'NSE';
            }
            $exchange = $seg->exchange;
            $organizedTrades->where('exchange', $exchange);
        }

        if($request->has('expiry_date_id'))
        {
            $expiry = ExpiryDate::find($request->expiry_date_id);
            $tradeSymbol = $expiry->tradingsymbol;
            $organizedTrades->where('tradingsymbol', $tradeSymbol);

        }

        if($request->has('name'))
        {
            $search = $request->name;
            $organizedTrades->whereHas('user', function ($query) use ($search) {
                $query->where('first_name', 'like', '%' . $search . '%')
                      ->orWhere('last_name', 'like', '%' . $search . '%');
            });
        }

        $organizedTrades=$organizedTrades->paginate(\Request::get('page_size')?\Request::get('page_size'):10);

        return view('pages.Closed.index', compact('organizedTrades','segment'));
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
