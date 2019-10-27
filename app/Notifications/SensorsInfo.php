<?php

namespace App\Notifications;

use App\Mail\NotificationMail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Class SensorsInfo
 * @package App\Notifications
 *
 * Для нотификаций от сенсоров. Предельные показания или что-то не сработало.
 */
class SensorsInfo extends Notification
{
//    use Queueable;

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
        return ['database', 'mail'];
    }


    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return NotificationMail
     */
    public function toMail($notifiable)
    {
        return (new NotificationMail($this->notifyMessage, $this->notifyType));
    }

    public function toDatabase($notifiable)
    {
        return [
            'notify' => $this->notifyMessage,
            'date' => date("d.m.Y H:i:s"),
        ];
    }
}
