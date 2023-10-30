<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AntrianDiantrikan extends Notification
{
    use Queueable;

    protected $antrian;
    /**
     * Create a new notification instance.
     */
    public function __construct($antrian)
    {
        $this->antrian = $antrian;
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
            'title' => 'Antrian Workshop',
            'message' => 'Ada antrian'. $this->antrian->job->job_name . 'dari sales'. $this->antrian->sales->sales_name . 'silahkan di proses, jangan lupa cek spesifikasinya ya!',
            'link' => '/antrian',
        ];
    }
}
