<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Segment;
use App\Models\Script;
use App\Models\User;
use App\Models\Notification;
use App\Models\NotificationLog;


class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users = User::where('role_id','1')->get();
        $notification = Notification::paginate($request->get('page_size', 10));
        
        return view('pages.Notification.index', compact('users','notification'))->with('isWelcomePage', false)->with('isvideo', false);;
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
        try {
            $notification = new Notification();
            $notification->title = $request->title;
            $notification->message = $request->message;
            $notification->send_at = $request->send_at;
            $notification->save();

            $userIds = $request->input('users', []);
           
            foreach ($userIds as $userId) {
                $notificationLog = new NotificationLog();
                $notificationLog->user_id = $userId;
                $notificationLog->notification_id = $notification->id;
                $notificationLog->save();
           
            }
            return redirect()->back()->with('success', 'Notification Created Successfully');
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
        $users = User::where('role_id','1')->get();
        $notification = Notification::find($id);
        return view('pages.Notification.edit', compact('users','notification'))->with('isWelcomePage', false)->with('isvideo', false);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
{
    try {
       
        $notification = Notification::find($id);
        $notification->title = $request->title;
        $notification->message = $request->message;
        $notification->send_at = $request->send_at;
        $notification->save();

 
        $selectedUsers = $request->users ?? [];
        $currentUsers = NotificationLog::where('notification_id', $id)->where('deleted_at',null)->pluck('user_id')->toArray();

        $usersToAdd = array_diff($selectedUsers, $currentUsers);
      
        $usersToRemove = array_diff($currentUsers, $selectedUsers);

        foreach ($usersToAdd as $userId) {
            $notificationLog = new NotificationLog();
            $notificationLog->notification_id = $notification->id;
            $notificationLog->user_id = $userId;
            $notificationLog->save();
        }

        foreach ($usersToRemove as $userId) {
            NotificationLog::where('notification_id', $notification->id)
                ->where('user_id', $userId)
                ->update(['deleted_at' => now()]);
        }

        return redirect()->route('notification.index')->with('success', 'Notification Updated Successfully');
    } catch (\Throwable $th) {
        return redirect()->back()->withInput()->with('error', 'Something Went Wrong');
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
