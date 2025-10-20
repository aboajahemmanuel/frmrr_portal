<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;

class NotifyAppDisable extends Mailable
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


        return $this->view('emails.Email_App_Disable')
            ->with([
                'title' => $title,
                'appName' => $this->email_data['appName'],
                'employee_name' => $this->email_data['employee_name'],
                'role' => $this->email_data['role'],
                'employee_email' => $this->email_data['employee_email']

            ])
            ->subject("Notification");
    }
}
