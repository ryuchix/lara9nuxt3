<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactUs extends Mailable
{
    use Queueable, SerializesModels;
    
    public $details;
    public $sender;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details, $sender)
    {
        $this->details = $details;
        $this->sender = $sender;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.contact-us')
                    ->from($this->sender, 'A.N.Shell Realty')
                    ->with('details', $this->details);
    }
}
