@extends('layouts.app')

@section('meta_title', 'Регистрация — '.setting('shop_name'))

@section('breadcrumbs')
    @include('front.partials.breadcrumbs', ['items' => [['title' => 'Регистрация', 'url' => url('/register')]]])
@endsection

@section('content')
    <div class="auth-page py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="card border-0 shadow-sm p-4 p-md-5">
                        <h1 class="h3 mb-4 text-center">Регистрация</h1>

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Имя и фамилия</label>
                                <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required autofocus>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required>
                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Телефон <span class="text-muted">(необязательно)</span></label>
                                    <input type="tel" name="phone" value="{{ old('phone') }}" class="form-control @error('phone') is-invalid @enderror">
                                    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Пароль</label>
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Повторите пароль</label>
                                    <input type="password" name="password_confirmation" class="form-control" required>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 btn-lg">Зарегистрироваться</button>
                        </form>

                        <p class="text-center text-muted mt-4 mb-0">
                            Уже есть аккаунт? <a href="{{ route('login') }}">Войти</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
