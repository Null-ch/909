@extends('layouts.app')

@section('meta_title', $metaTitle)

@section('breadcrumbs')
    @include('front.partials.breadcrumbs', ['items' => [
        ['title' => 'Личный кабинет', 'url' => route('account.dashboard')],
        ['title' => 'Профиль', 'url' => route('account.profile.edit')],
    ]])
@endsection

@section('content')
    <div class="account-page py-4">
        <div class="container">
            <h1 class="h2 mb-4">Личный кабинет</h1>

            <div class="row g-4">
                <div class="col-lg-3">
                    @include('account.partials.nav')
                </div>

                <div class="col-lg-9">
                    <div class="card border-0 shadow-sm p-4">
                        <h2 class="h5 mb-3">Мои данные</h2>

                        <form method="POST" action="{{ route('account.profile.update') }}">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Имя и фамилия</label>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Телефон</label>
                                    <input type="tel"
                                           name="phone"
                                           value="{{ old('phone', $user->phone) }}"
                                           class="form-control js-phone-input @error('phone') is-invalid @enderror"
                                           placeholder="+7 (___) ___-__-__"
                                           inputmode="tel"
                                           autocomplete="tel"
                                           maxlength="18">
                                    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror" required>
                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    <div class="form-text">При смене email потребуется подтвердить его заново.</div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <h3 class="h6 mb-3">Смена пароля <span class="text-muted fw-normal">(необязательно)</span></h3>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Новый пароль</label>
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Повторите пароль</label>
                                    <input type="password" name="password_confirmation" class="form-control">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary mt-4">Сохранить изменения</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
