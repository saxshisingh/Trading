<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Wallet;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransactionExport;

class TransactionController extends Controller
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
        $wallets = Wallet::with('user');

        if($request->after!=NULL)
        {
            $afterDate = $request->after;
            $wallets->whereDate('created_at', '>=', $afterDate);
        }
        if($request->before!=NULL)
        {
            $beforeDate = $request->before;
            $wallets->whereDate('created_at', '>=', $beforeDate);
        }
        if($request->user_id!=NULL)
        {
            $wallets->where('user_id',$request->user_id);
        }
        $wallet = $wallets->get();

        return view('pages.History.transaction_history', compact('wallet', 'users'))->with('isWelcomePage', false)->with('isvideo', false);
    }

    /**
     * Show the form for creating a new resource.
     */

     public function export()
    {
        return Excel::download(new TransactionExport , 'transaction_history.xlsx');
    }

    public function create(Request $request)
    {
        $filters = [
            'after' => $request->input('after'),
            'before' => $request->input('before'),
            'user_id' => $request->input('user_id'),
        ];
        return Excel::download(new TransactionExport($filters) , 'transaction_history.xlsx');
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
