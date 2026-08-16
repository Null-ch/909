<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Services\AccountService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function __construct(
        private readonly AccountService $accountService,
    ) {}

    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $result = $this->accountService->createAccount([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
        ], $data['password']);

        $user = $result['user'];

        Auth::guard('web')->login($user);
        $request->session()->regenerate();

        $user->sendEmailVerificationNotification();

        return redirect()->route('verification.notice');
    }
}
