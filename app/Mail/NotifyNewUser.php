<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifyNewUser extends Mailable
{
    use Queueable, SerializesModels;

    public $email_data;

    public function __construct($email_data)
    {
        $this->email_data = $email_data;
    }

    public function build()
    {
        return $this->view('emails.usercreation')
            ->with([
                'title' => $this->email_data['title'] ?? 'Default Title', // Default title
                'name' => $this->email_data['name'],
                'email' => $this->email_data['email'],
                'password' => $this->email_data['password'],
                'created_at' => $this->email_data['created_at'],
            ])
            ->subject($this->email_data['title'] ?? 'Default Subject'); // Default subject
    }
}
