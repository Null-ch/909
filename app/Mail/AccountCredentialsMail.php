<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccountCredentialsMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly User $user,
        public readonly string $password,
    ) {}

    public function build(): self
    {
        return $this
            ->subject('Ваш аккаунт на '.setting('shop_name', config('app.name')))
            ->view('emails.account-credentials');
    }
}
