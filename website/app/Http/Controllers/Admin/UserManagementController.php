<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::where('role_id', '1');
    
        if ($request->name) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'LIKE', '%' . $request->name . '%')
                  ->orWhere('last_name', 'LIKE', '%' . $request->name . '%');
            });
        }
    
        if ($request->phone) {
            $query->where('phone', $request->phone);
        }
    
        if ($request->email) {
            $query->where('email', $request->email);
        }
    
        if ($request->status !== null) {
            $query->where('status', $request->status);
        }
    
        $users = $query->paginate($request->get('page_size', 10));
    
        return view('pages.Member.index', compact('users', 'request'))
            ->with('isWelcomePage', false)
            ->with('isvideo', false);
    }
    

    /**
     * Show the form for creating a new resource.
     */

    public function toggleActive(Request $request, $id)
    {
        try {
            $user = User::where('id',$id)->first();
            if($user->status=='1'){
                $user->status='0';
            }else{
                $user->status='1';
            }
            if($user->save()){
                return \Redirect::back()->withInput(['success' => 'User '.($user->status==0?'Deactivated':'Activated')]);
            }else{
                return \Redirect::back()->withInput(['error' => 'Something Went Wrong']);
            }
        } catch (\Throwable $th) {
            dd($th);
            return \Redirect::back()->withInput(['error' => 'Something Went Wrong']);
        }
    }

    public function create()
    {
        return view('pages.Member.create')->with('isWelcomePage', false)->with('isvideo', false);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
  
        try{
            if(!isset($request->id))
            {
            $validator = \Validator::make($request->all(), [
                'first_name'=>'required',
                    'last_name'=>'required',
                    'client'=>'required',
                    'transaction_password'=>'required',
                    'type'=>'required',
                    'phone' => 'required|digits:10|unique:users',
                    'password' => 'required',
                    'confirm_password' => 'required',                
             ]);
            }else{
                $validator = \Validator::make($request->all(), [
                   'first_name'=>'required',
                    'last_name'=>'required',
                    'client'=>'required',
                    'transaction_password'=>'required',
                    'type'=>'required',
                    'phone' => 'required|digits:10|unique:users',
                    'password' => 'required',
                    'confirm_password' => 'required',
                 ]);
            }
             if ($validator->fails()) { 
                return \Redirect::back()->withErrors(['error' => $validator->errors()->first()])->withInput(\Request::all());
              }
              if(isset($request->id))
                {
                    $status = "Updated";
                    $data = User::find($request->id);
                }else{
                    $find = User::where('phone', $request->phone)->first();
                    if($find){
                        return \Redirect::back()->withInput(['error' => 'Mobile Number Already Registered']);
                    }
                    $status = "Saved";
                    $data = new User;
                }

                if($request->password === $request->confirm_password){

                    $data->password = Hash::make($request->password);
                    $data->role_id = '1';
                    $data->first_name = $request->first_name;
                    $data->last_name = $request->last_name;
                    $data->phone = $request->phone;                   
                    $data->client = $request->client;
                    $data->transaction_password = \Hash::make($request->transaction_password);
                    $data->type = $request->type;
                    $data->auto_close_limit = $request->auto_close_limit;
                    $data->charge_per_lot = $request->charge_per_lot;
                    $data->charge_per_lot_mcx = $request->charge_per_lot_mcx;
                    $data->notify_loss_limit = $request->notify_loss_limit;
                    $data->p_l_share = $request->p_l_share;
                    $data->brokerage_share = $request->brokerage_share;
                    $data->trading_client_limit = $request->trading_client_limit;
                    $data->sub_broker_limit = $request->sub_broker_limit;
                    $data->sub_broker_action = $request->sub_broker_action;
                    $data->payin_allow = $request->payin_allow;
                    $data->payout_allow = $request->payout_allow;
                    $data->create_client_allow = $request->create_client_allow;
                    $data->client_task_allow = $request->client_task_allow;
                    $data->trade_activity_allow = $request->trade_activity_allow;
                    $data->notification_allow = $request->notification_allow;
                    $data->mcx_trade = $request->mcx_trade;
                    $data->mcx_broker_type = $request->mcx_broker_type;
                    $data->mcx_brokerage = $request->mcx_brokerage;
                    $data->exposure_mcx_type = $request->exposure_mcx_type;
                    $data->intraday_margin_mcx = $request->intraday_margin_mcx;
                    $data->holding_margin_mcx = $request->holding_margin_mcx;
                    $data->equity_trading = $request->equity_trading;
                    $data->equity_borkerage_per_crore = $request->equity_borkerage_per_crore;
                    $data->intraday_margin_equity = $request->intraday_margin_equity;
                    $data->holding_margin_equity = $request->holding_margin_equity;
                    $data->save();
                    if($data->save()){
                        return \Redirect::back()->withInput(['success' => "Member Successfully"]);
                    }
                    else{
                        return \Redirect::back()->withInput(['error' => 'Sorry User Not Save Try Again']);
                    }
                }
                else{
                    return \Redirect::back()->withInput(['error' => 'Confirm Password and Password should be same']);
                }
        }
        catch (\Throwable $th) {
            return \Redirect::back()->withInput(['error' => 'Sorry User Not Save Try Again']);
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
        $users = User::find($id);
        return view('pages.Member.create', compact('users'))->with('isWelcomePage', false)->with('isvideo', false);
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
