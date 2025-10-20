<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;

class NotifyUser extends Mailable
{
    use Queueable, SerializesModels;

    public $email_data; // assuming this is an array containing the necessary data

    public function __construct($email_data)
    {
        $this->email_data = $email_data;
    }

    public function build()
    {
        Log::info('Building email with data:', $this->email_data);

        if (!isset($this->email_data['title'])) {
            Log::error('Title is not set in email data.');
            $title = 'Default Title'; // Fallback title if not set
        } else {
            $title = $this->email_data['title'];
        }


        return $this->view('emails.Email_Create')
            ->with([
                'title' => $title,
                'action' => $this->email_data['action'],
                'email' => $this->email_data['email'],
            ])
            ->subject("Notification");
    }
}
