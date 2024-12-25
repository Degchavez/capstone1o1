<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $randomPassword;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $randomPassword)
    {
        $this->user = $user;
        $this->randomPassword = $randomPassword;
    }

    /**
     * Build the message.
     *
     * @return \Illuminate\Mail\Message
     */
    public function build()
    {
        return $this->subject('Welcome to Our City Veterinarys Office, Valencia City')
                    ->view('emails.welcome'); // Create a view for the email body
    }
}
