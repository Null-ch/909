@extends('layouts.app')

@section('meta_title', $metaTitle)
@section('meta_description', $metaDescription)

@section('breadcrumbs')
    @include('front.partials.breadcrumbs', ['items' => [['title' => 'О компании', 'url' => url('/about')]]])
@endsection

@section('content')
    <div class="static-page py-4">
        <div class="container">
            <h1 class="h2 section-title mb-4">О компании</h1>

            <div class="static-page__content">
                {!! setting('about_text') !!}
            </div>
        </div>
    </div>
@endsection
