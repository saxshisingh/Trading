<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class TransPasswordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::find(auth()->user()->id);
        return view('pages.Password.trans', compact('user'));
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
      
        try {
            $user = User::find($id);
            
            if (!$user) {
                return \Redirect::back()->withErrors(['error' => 'User not found']);
            }

            if (!\Hash::check($request->input('trans_password'), $user->trans_password)) {
                return \Redirect::back()->withErrors(['error' => 'Current transaction password is incorrect']);
            }

            if ($request->input('new_trans_password') !== $request->input('confirm_password')) {
                return \Redirect::back()->withErrors(['error' => 'New password and confirm password do not match']);
            }

            $user->trans_password = \Hash::make($request->input('new_trans_password'));
            $user->save();

            return \Redirect::back()->with('success', 'Password updated successfully');

        } catch (\Throwable $th) {
            dd($th);
            return \Redirect::back()->withErrors(['error' => 'Something went wrong']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
