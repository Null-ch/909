@extends('layouts.app')

@section('meta_title', 'Вход — '.setting('shop_name'))

@section('breadcrumbs')
    @include('front.partials.breadcrumbs', ['items' => [['title' => 'Вход', 'url' => url('/login')]]])
@endsection

@section('content')
    <div class="auth-page py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-7">
                    <div class="card border-0 shadow-sm p-4 p-md-5">
                        <h1 class="h3 mb-4 text-center">Вход в личный кабинет</h1>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required autofocus>
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Пароль</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="form-check mb-4">
                                <input type="checkbox" name="remember" value="1" class="form-check-input" id="remember">
                                <label class="form-check-label" for="remember">Запомнить меня</label>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 btn-lg">Войти</button>
                        </form>

                        <p class="text-center text-muted mt-4 mb-0">
                            Нет аккаунта? <a href="{{ route('register') }}">Зарегистрироваться</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
