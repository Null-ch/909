@extends('layouts.app')

@section('meta_title', 'Подтверждение email — '.setting('shop_name'))

@section('content')
    <div class="auth-page py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="card border-0 shadow-sm p-4 p-md-5 text-center">
                        <div class="order-success__icon mb-3">
                            <i class="fa-solid fa-envelope-circle-check"></i>
                        </div>
                        <h1 class="h4 mb-3">Подтвердите email</h1>

                        <p class="text-muted">
                            Мы отправили письмо со ссылкой для подтверждения на {{ auth()->user()->email }}.
                            Перейдите по ссылке из письма, чтобы подтвердить почту.
                        </p>

                        <form method="POST" action="{{ route('verification.send') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-primary">Отправить письмо повторно</button>
                        </form>

                        <a href="{{ route('account.dashboard') }}" class="btn btn-link mt-2">Перейти в личный кабинет</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
