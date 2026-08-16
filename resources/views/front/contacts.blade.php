@extends('layouts.app')

@section('meta_title', $metaTitle)
@section('meta_description', $metaDescription)

@section('breadcrumbs')
    @include('front.partials.breadcrumbs', ['items' => [['title' => 'Контакты', 'url' => url('/contacts')]]])
@endsection

@section('content')
    <div class="static-page py-4">
        <div class="container">
            <h1 class="h2 section-title mb-4">Контакты</h1>

            <div class="row g-4">
                <div class="col-lg-5">
                    <ul class="list-unstyled static-page__contacts">
                        @if($phone = setting('contact_phone'))
                            <li class="mb-3">
                                <i class="fa-solid fa-phone me-2 text-green"></i>
                                <a href="tel:{{ preg_replace('/[^\d+]/', '', $phone) }}">{{ $phone }}</a>
                            </li>
                        @endif
                        @if($email = setting('contact_email'))
                            <li class="mb-3">
                                <i class="fa-solid fa-envelope me-2 text-green"></i>
                                <a href="mailto:{{ $email }}">{{ $email }}</a>
                            </li>
                        @endif
                        @if($address = setting('contact_address'))
                            <li class="mb-3">
                                <i class="fa-solid fa-location-dot me-2 text-green"></i>
                                {{ $address }}
                            </li>
                        @endif
                    </ul>
                </div>

                @if($map = setting('contact_map_iframe'))
                    <div class="col-lg-7">
                        <div class="static-page__map">
                            {!! $map !!}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
