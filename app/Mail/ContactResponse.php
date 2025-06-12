<?php

namespace App\Mail;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactResponse extends Mailable
{
    use Queueable, SerializesModels;

    public $contact;
    public $response;

    public function __construct(Contact $contact, $response)
    {
        $this->contact = $contact;
        $this->response = $response;
    }

    public function build()
    {
        return $this->subject('Phản hồi từ Admin - ' . $this->contact->subject)
                    ->view('emails.contact-response');
    }
}
