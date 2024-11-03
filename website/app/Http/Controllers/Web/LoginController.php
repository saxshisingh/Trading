<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;

use Illuminate\Http\Request;
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

    public function generateToken(){
        $loginId = 'DC-MOHD8340'; // use your login ID.
        $product = 'DIRECTRTLITE';
        $apikey = '05546177F7514194AC29'; // use your API Key
        $authEndPoint = "http://s3.vbiz.in/directrt/gettoken?loginid=$loginId&product=$product&apikey=$apikey";
        $response = file_get_contents($authEndPoint);
        return $response;
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       
        try {
            $validator = \Validator::make($request->all(), [
                'user_id' => 'required',
                'password' => 'required',
             ]);
             if ($validator->fails()) { 
                return \Redirect::back()->withInput(['error' => $validator->errors()->first()]); 
             }

                if(Auth::attempt(['client' => request('user_id'), 'password' => request('password')])){ 
                    $user = Auth::user();
                    $user->api_token=\Str::random(60); 
                    $user->save();
                    if($user->role_id=='2')
                    {
                    return redirect()->intended('/dashboard');
                    }
                    else{
                        return redirect()->intended('/');
                    }
                } 
                else{ 
                    return \Redirect::back()->withInput(['error'=>'Unauthorised']);
                }
        }catch (\Throwable $th) {
            dd($th);
            return \Redirect::back()->withInput(['error'=>'something went wrong']);
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
