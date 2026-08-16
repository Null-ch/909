<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAccountProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        return view('account.profile', [
            'user' => Auth::user(),
            'metaTitle' => 'Профиль — '.setting('shop_name'),
        ]);
    }

    public function update(UpdateAccountProfileRequest $request): RedirectResponse
    {
        $user = Auth::user();
        $data = $request->validated();

        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        $emailChanged = $data['email'] !== $user->email;

        $user->update($data);

        if ($emailChanged) {
            $user->forceFill(['email_verified_at' => null])->save();
            $user->sendEmailVerificationNotification();
        }

        return redirect()
            ->route('account.profile.edit')
            ->with('success', 'Данные профиля обновлены.');
    }
}
