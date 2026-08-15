@props([
    'items' => [],
])

@if(!empty($items))
    <nav class="site-breadcrumbs" aria-label="Хлебные крошки">
        <div class="container">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ url('/') }}">Главная</a>
                </li>
                @foreach($items as $item)
                    @if($loop->last)
                        <li class="breadcrumb-item active" aria-current="page">{{ $item['title'] }}</li>
                    @else
                        <li class="breadcrumb-item">
                            <a href="{{ $item['url'] }}">{{ $item['title'] }}</a>
                        </li>
                    @endif
                @endforeach
            </ol>
        </div>
    </nav>
@endif
