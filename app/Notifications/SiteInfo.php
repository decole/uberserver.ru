<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Class SiteInfo
 * @package App\Notifications
 *
 * Для всех объявлений, которые нужно отобразить на сайте.
 */
class SiteInfo extends Notification
{
    use Queueable;

    public $notifyMessage;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($message)
    {
        $this->notifyMessage = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'notify' => $this->notifyMessage,
            'date' => date("d.m.Y H:i:s"),
        ];
    }

}
