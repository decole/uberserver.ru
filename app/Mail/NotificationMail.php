<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
//use Illuminate\Queue\SerializesModels;
//use Illuminate\Contracts\Queue\ShouldQueue;

class NotificationMail extends Mailable
{
    //use Queueable, SerializesModels;

    public $notifyMessage;
    public $notifyType;
    public $fileLogNotify;

    /**
     * Create a new message instance.
     *
     * @param $message
     * @param $type
     * @param null $fileLogNotify
     */
    public function __construct($message, $type, $fileLogNotify = null)
    {
        $this->notifyMessage = $message;
        $this->notifyType = $type;
        $this->fileLogNotify = $fileLogNotify;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if($this->fileLogNotify) {
            $this->attach($this->fileLogNotify, [
                'as' => 'log_' . date('Y-m-d') . '.txt',
                'mime' => 'text/plain',
            ]);
        }
        return $this
            ->to('decole@rambler.ru')
            ->subject('Notification UberServer.ru')
            ->view('emails.notification.mail_1');

    }
}
