@extends('layouts.gentelella')



@section('title', 'Админ-панель')

@section('page', 'dashboard')

@section('breadcrumb', 'Главная > Панель управления')



@section('content')

    <div class="page-header">

        <div class="page-header-row">

            <div>

                <div class="page-pretitle">Обзор</div>

                <h1 class="page-title">Панель управления</h1>

            </div>

            <div class="page-actions">

                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">

                    <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">

                        <path d="M8 2v12M2 8h12"/>

                    </svg>

                    Новый пользователь

                </a>

            </div>

        </div>

    </div>



    <div class="row col-3">

        <div class="card">

            <div class="stat">

                <div class="stat-icon teal">

                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">

                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>

                        <circle cx="9" cy="7" r="4"/>

                    </svg>

                </div>

                <div class="stat-content">

                    <div class="stat-label">Пользователи</div>

                    <div class="stat-value-row">

                        <span class="stat-value">{{ $usersCount }}</span>

                    </div>

                    <div class="stat-subtext">Всего в системе</div>

                </div>

            </div>

        </div>

        <div class="card">

            <div class="stat">

                <div class="stat-icon blue">

                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">

                        <circle cx="12" cy="12" r="10"/>

                        <polyline points="12 6 12 12 16 14"/>

                    </svg>

                </div>

                <div class="stat-content">

                    <div class="stat-label">Роль</div>

                    <div class="stat-value-row">

                        <span class="stat-value" style="font-size:18px">Администратор</span>

                    </div>

                    <div class="stat-subtext">{{ auth()->user()->email }}</div>

                </div>

            </div>

        </div>

        <div class="card">

            <div class="stat">

                <div class="stat-icon yellow">

                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">

                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>

                    </svg>

                </div>

                <div class="stat-content">

                    <div class="stat-label">Доступ</div>

                    <div class="stat-value-row">

                        <span class="stat-value" style="font-size:18px">Защищён</span>

                    </div>

                    <div class="stat-subtext">Требуется авторизация</div>

                </div>

            </div>

        </div>

    </div>



    <div class="card" style="margin-top:var(--gap)">

        <div class="card-header">

            <h3 class="card-title">Быстрые действия</h3>

        </div>

        <div class="card-body">

            <p style="margin:0 0 16px;color:var(--text-muted)">

                Вы вошли как <strong>{{ auth()->user()->name }}</strong>.

                Управляйте пользователями системы из раздела ниже.

            </p>

            <a href="{{ route('admin.users.index') }}" class="btn btn-outline">

                Перейти к пользователям

            </a>

        </div>

    </div>

@endsection

