<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Mail\AccountCredentialsMail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AccountService
{
    /**
     * @param  array{name: string, email: string, phone?: ?string}  $data
     * @return array{user: User, password: string}
     */
    public function createAccount(array $data, ?string $password = null): array
    {
        $plainPassword = $password ?: Str::password(12);

        $user = User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($plainPassword),
            'role' => UserRole::User,
        ]);

        logActivity('created', 'User', $user->id, "Зарегистрирован аккаунт покупателя «{$user->email}»");

        return ['user' => $user, 'password' => $plainPassword];
    }

    public function sendCredentialsEmail(User $user, string $password): void
    {
        Mail::to($user->email)->send(new AccountCredentialsMail($user, $password));
    }
}
