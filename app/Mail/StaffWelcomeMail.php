<?php

namespace App\Mail;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StaffWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $password;
    public $organization;
    public $orgName;

    public function __construct(User $user, $password, ?Organization $organization = null)
    {
        $this->user = $user;
        $this->password = $password;
        $this->organization = $organization;
        $this->orgName = $organization ? $organization->name : config('app.name', 'P23 Africa');
    }

    public function build()
    {
        return $this->subject('Welcome to ' . $this->orgName . ': Your Account Details')
            ->view('emails.staff_welcome');
    }
}
