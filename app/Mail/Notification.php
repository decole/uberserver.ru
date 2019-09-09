<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Notification extends Mailable
{
    use Queueable, SerializesModels;

    public $notifyMessage;
    public $notifyType;

    /**
     * Create a new message instance.
     *
     * @param $message
     * @param $type
     */
    public function __construct($message, $type)
    {
        $this->notifyMessage = $message;
        $this->notifyType = $type;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from('decole@rambler.ru')
            ->subject('Notification UberServer.ru')
            ->view('emails.notification.mail_1');
    }
}
