<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Employee;

class WelcomMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $employee; 
    protected $password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Employee $employee,$setPassword)
    {
        $this->employee = $employee;
        $this->password = $setPassword;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.users.welcommail')->with(['firstname' => $this->employee->first_name,'email'=> $this->employee->email,'password' => $this->password]);
    }
}
