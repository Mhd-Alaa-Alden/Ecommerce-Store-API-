<?php

namespace App\Services;

use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FcmService
{
    public static function notify($title, $body, $target, $image = null)
    {

        if (! $target) {
            return 1;
        }
        $messaging = app('firebase.messaging');

        $notification = Notification::create($title, $body, $image);

        $message = CloudMessage::new()
            ->withNotification($notification)
            ->withData(['type' => 'new message']);

        $report = $messaging->sendMultiCast($message, $target);

        if ($report->successes()->count() . PHP_EOL) {
            // return 'Successful sends: ' . $report->successes()->count() . PHP_EOL;
        } else {
            // return 'Failed sends: ' . $report->failures()->count() . PHP_EOL;
        }

        $errors = '';
        if ($report->hasFailures()) {
            foreach ($report->failures()->getItems() as $failure) {
                $errors = $errors . $failure->error()->getMessage() . PHP_EOL;
            }
        } else {
        }

        return $errors;
    }
}



























// _______________________________________________________________________________-
// namespace App\Notifications;

// use Illuminate\Bus\Queueable;
// use Illuminate\Contracts\Queue\ShouldQueue;
// use Illuminate\Notifications\Notification;
// use Kreait\Firebase\Messaging\CloudMessage;
// use Kreait\Firebase\Messaging\Notification as FcmNotification;

// class SendPushNotification extends Notification
// {
//     use Queueable;

//     public function __construct()
//     {
//         //
//     }

//     public function via(object $notifiable): array
//     {
//         return ['fcm'];  // تعديل القناة لتشمل FCM
//     }

//     public function toFcm($notifiable)
//     {
//         return CloudMessage::withTarget('token', $notifiable->fcm_token)
//             ->withNotification(FcmNotification::create('New Update!', 'This is the notification body.'));
//     }

//     public function toArray(object $notifiable): array
//     {
//         return [
//             'message' => 'This is a FCM notification',
//         ];
//     }
// }
