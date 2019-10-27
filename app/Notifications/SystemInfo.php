<?php

namespace App\Notifications;

use App\Mail\NotificationMail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Class SystemInfo
 * @package App\Notifications
 *
 * Нотификаци отправляет электронную почту
 * Для нотификации от системных сервисов. Нужно если что-то отвалилось или не работает.
 */
class SystemInfo extends Notification
{
    //use Queueable;

    public $notifyMessage;
    public $notifyType;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($message, $type)
    {
        $this->notifyMessage = $message;
        $this->notifyType = $type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @return NotificationMail
     */
    public function toMail($notifiable)
    {
        return (new NotificationMail($this->notifyMessage, $this->notifyType));
    }

}
