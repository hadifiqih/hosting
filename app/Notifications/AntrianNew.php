<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Pusher\PushNotification\PushNotification;

use NotificationChannels\PusherPushNotifications\PusherChannel;
use NotificationChannels\PusherPushNotifications\PusherMessage;

class AntrianNew extends Notification
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

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }

    /**
     * Get the Pusher Push Notification representation of the notification.
     *
     * @return \NotificationChannels\PusherPushNotifications\PusherMessage
     */
    public function toPushNotification(object $notifiable): PusherMessage
    {
        return PusherMessage::create()
            ->title('Antrian Baru')
            ->body('Antrian baru telah ditambahkan! Silahkan cek daftar antrian.')
            ->badge(1)
            ->data(['extra_data' => 'Antrian dibuat oleh ' . auth()->user()->name]);
    }
}
