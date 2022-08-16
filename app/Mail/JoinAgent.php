<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class JoinAgent extends Mailable
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
        return $this->markdown('emails.join-agent')
                    ->from($this->sender, 'A.N.Shell Realty')
                    ->with('details', $this->details);
    }
}
