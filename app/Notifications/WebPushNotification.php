<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\PusherPushNotifications\PusherChannel;
use NotificationChannels\PusherPushNotifications\PusherMessage;

class WebPushNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [PusherChannel::class];
    }

    public function toPushNotification($notifiable)
    {
        $messages = 'Ada antrian baru nih, cek sekarang yuk!';

        return PusherMessage::create()
            ->web()
            ->title('Antree App Official')
            ->body($messages)
            ->withAndroid(
                PusherMessage::create()
                    ->title('Antree App Official')
                    ->body($messages)
            );
    }
}
