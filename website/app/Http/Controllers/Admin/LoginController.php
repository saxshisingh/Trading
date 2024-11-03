<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;

use Auth;


class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.Login.login');
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
        dd($request->all());
        dd(\Hash::make('123456'));
        try {
            $validator = \Validator::make($request->all(), [
                'user_id' => 'required',
                'password' => 'required',
             ]);
             if ($validator->fails()) { 
                return \Redirect::back()->withInput(['error' => $validator->errors()->first()]); 
             }
        $user = User::with('role')->where('id', $request->user_id)->first();
        if($user && $user->role->name=="admin"){
            $hashedPassword =\Hash::make($request->password);
            if (\Hash::check($request->password, $user->password)){
                if(Auth::attempt(['id' => request('user_id'), 'password' => request('password')])){ 
                    $user = Auth::user();
                    $user->api_token=\Str::random(60); 
                    $user->save();
                    return redirect()->intended('/dashboard');

                } 
                else{ 
                    dd("unauth");
                    return \Redirect::back()->withInput(['error'=>'Unauthorised']);
                }
             
            }else{
                dd("wrong pass");
             return \Redirect::back()->withInput(['error'=>'wrong password']);
            }
          }else{
            dd("not match");
             return \Redirect::back()->withInput(['error'=>'userid or Password Not Match']);
          }
        }catch (\Throwable $th) {
            dd($th);
            return \Redirect::back()->withInput(['error'=>'something went wrong'.$th]);
        }
    }


    public function logout(){
        Auth::logout();
        return redirect('/login')->withInput(['success' => 'Successfully Logout account']);;
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
