<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AntrianWorkshop extends Notification
{
    use Queueable;

    protected $antrian;
    protected $order;
    protected $payment;

    /**
     * Create a new notification instance.
     */
    public function __construct($antrian, $order, $payment)
    {
        $this->antrian = $antrian;
        $this->order = $order;
        $this->payment = $payment;
        $this->afterCommit();
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
            'title' => 'Antrian Workshop',
            'message' => 'Ada antrian workshop, '.$this->order->job->job_name . ' dengan judul '. $this->order->title,
            'link' => '/antrian'
        ];
    }
}
