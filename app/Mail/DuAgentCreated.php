<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DuAgentCreated extends Mailable
{
    use Queueable, SerializesModels;

    public User   $agent;
    public string $plainPassword;

    /**
     * @param User   $agent         The newly created user
     * @param string $plainPassword Their plaintext password
     */
    public function __construct(User $agent, string $plainPassword)
    {
        $this->agent          = $agent;
        $this->plainPassword  = $plainPassword;
    }

    public function build()
    {
        $district = optional($this->agent->organisation->district)->name ?? '—';

        return $this->subject("You’ve been registered as a DU-Agent")
                    ->markdown('emails.du_agent_created')
                    ->with([
                        'name'       => $this->agent->first_name . ' ' . $this->agent->last_name,
                        'email'      => $this->agent->email,
                        'password'   => $this->plainPassword,
                        'district'   => $district,
                    ]);
    }
}
