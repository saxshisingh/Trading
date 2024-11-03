<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\User;

class TraderFundController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $wallets = Wallet::with('user')->where('category','fund');
        $users = User::where('role_id','1')->get();

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
        
        if($request->amount!=NULL){
            $wallets->where('amount','=',$request->amount);
        }
        $wallet = $wallets->get();
      
        // $wallet = $wallet->paginate(\Request::get('page_size')?\Request::get('page_size'):10);
        return view('pages.Funds.index', compact('wallet', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = User::where('role_id','1')->get();
       
        return view('pages.Funds.create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{

            $admin = User::where('role_id', 1)->first();
            if (!$admin || !\Hash::check($request->transaction_password, $admin->transaction_password)) {
                return \Redirect::back()->withInput()->withErrors(['error' => 'Invalid Transaction Password']);
            }
            $wallet = new Wallet();
            $wallet->user_id = $request->user_id;
            $wallet->amount = $request->amount;
            $wallet->category = 'fund';
            $wallet->remarks = $request->remarks;
            $wallet->type = $request->type;
            $wallet->action = 'fund';
            $wallet->status = "success";
            $wallet->save();
            $user = User::find($request->user_id);
            
            if($request->type=='credit'){
                $user->credit+=$request->amount;
                $user->balance+=$request->amount;
                $user->initial_balance = $user->balance;
                $user->position_balance = $user->balance;
                $user->save();
            }
            else{
                $user->debit+=$request->amount;
                $user->balance-=$request->amount;
                $user->initial_balance = $user->balance;
                $user->position_balance = $user->balance;
                $user->save();
            }
            return redirect()->route('trader_funds.index')->with('success', 'Fund transaction created successfully');
        }catch (\Throwable $th) {
            dd($th);
            return \Redirect::back()->withInput(['error' => 'Something Went Wrong']);
        }
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
