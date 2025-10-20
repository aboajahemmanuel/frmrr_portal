<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyUserUpdate extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $email_data; // assuming this is an array containing the necessary data

    public function __construct($email_data)
    {
        $this->email_data = $email_data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        Log::info('Building email with data:', $this->email_data);

        if (!isset($this->email_data['title'])) {
            Log::error('Title is not set in email data.');
            $title = 'Default Title'; // Fallback title if not set
        } else {
            $title = $this->email_data['title'];
        }

        return $this->view('emails.Email_Update')
            ->with([
                'title' => $title,
                'action' => $this->email_data['action'],
                'email' => $this->email_data['email'],
            ])
            ->subject("Notification");
    }
}
