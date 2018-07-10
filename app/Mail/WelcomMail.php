<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;

class WelcomMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $user; 
    protected $password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user,$setPassword)
    {
        $this->user = $user;
        $this->password = $setPassword;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.users.welcommail')->with(['firstname' => $this->user->firstname,'email'=> $this->user->email,'password' => $this->password]);
    }
}
