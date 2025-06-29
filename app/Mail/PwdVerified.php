<?php
// app/Mail/PwdVerified.php

namespace App\Mail;

use App\Models\Person;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PwdVerified extends Mailable
{
    use Queueable, SerializesModels;

    public Person $person;

    public function __construct(Person $person)
    {
        $this->person = $person;
    }

    public function build()
    {
        return $this->subject('Your ICT-PWD Account is Approved')
                    ->view('emails.pwd-verified', [
                        'name' => $this->person->name,
                        'email' => $this->person->email,
                    ]);
    }
}
