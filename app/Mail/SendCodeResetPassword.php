<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendCodeResetPassword extends Mailable
{
    use Queueable, SerializesModels;


    public $code;

    public function __construct($code)
    {
        $this->code = $code;
    }

    public function build()
    {
        return $this->markdown('email.send-code-reset-password')
            ->subject("Reset Password Code");
    }


    // public function envelope()
    // {
    //     return new Envelope(
    //         subject: 'Send Code Reset Password',
    //     );
    // }


    // public function content()
    // {
    //     return new Content(
    //         view: 'view.name',
    //     );
    // }

    // public function attachments()
    // {
    //     return [];
    // }
}
