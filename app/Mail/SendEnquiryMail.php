<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEnquiryMail extends Mailable
{
    use Queueable, SerializesModels;

   public $message;
    public function __construct($message)
    {
        $this->message = $message;
        // dd($message);
    }

    
    public function build()
    {
        $emailMessage = $this->message;

        return $this->subject('Enquiry For Service')->view('emails.enquiry', ['messages' => $emailMessage]);
    }
}
