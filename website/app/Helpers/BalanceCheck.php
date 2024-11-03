<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\Notification;
use App\Models\NotificationLog;

class BalanceCheck
{
    public static function checkAndNotify(User $user)
    {
        $initialBalance = $user->initial_balance;
        $limit = $user->notify_loss_limit;
        
        $notificationSent = false;
        if ($initialBalance > 0 && $user->balance <= ($initialBalance * ((100 - $limit) / 100))) {
            $notification = new Notification();
            $notification->title = "Balance Alert!!!";
            $notification->message = "Your balance is below 70%! Please top up to avoid interruptions.";
            $notification->send_at = now();
            $notification->save();

            $notificationLog = new NotificationLog();
            $notificationLog->user_id = $user->id;
            $notificationLog->notification_id = $notification->id;
            $notificationLog->save();
            $notificationSent = true;
            
        }
        // if($user->balance < 90)
        // {
        //     $notification = new Notification();
        //     $notification->title = "Balance Alert!!!";
        //     $notification->message = "Your balance is below 90%! All open positions are closed.";
        //     $notification->send_at = now();
        //     $notification->save();

        //     $notificationLog = new NotificationLog();
        //     $notificationLog->user_id = $user->id;
        //     $notificationLog->notification_id = $notification->id;
        //     $notificationLog->save();
        //     $notificationSent = true;
        // }
        return $notificationSent;
    }
}
