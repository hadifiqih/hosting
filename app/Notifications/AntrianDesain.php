<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AntrianDesain extends Notification
{
    use Queueable;

    protected $user;
    protected $sales;
    protected $order;

    /**
     * Create a new notification instance.
     */
    public function __construct($user, $sales, $order)
    {
        $this->afterCommit();
        $this->user = $user;
        $this->sales = $sales;
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Antrian Desain',
            'message' => $this->user->name . ', ada antrian desain untukmu dari ' . $this->sales->sales_name . ', cek sekarang!',
            'link' => '/design',
        ];
    }
}
