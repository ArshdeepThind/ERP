<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Admin;

class welcomeadmin extends Mailable
{
    use Queueable, SerializesModels;

    public $admin;
    public $pwd;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Admin $admin,$pwd)
    {
        $this->admin = $admin;
        $this->pwd   =$pwd;
    }

    public function setPassword($password){
        $this->pwd=$password;
        return $this;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.welcomeadmin')->with('password', $this->pwd)
            ->with('name', $this->admin->name);
    }
}
