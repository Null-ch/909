@php
    $hasLegalColumn = $legalName || $legalInn || $legalOgrn || $legalKpp || $legalAddress;
    $footerColClass = $hasLegalColumn ? 'col-lg-3 col-md-6' : 'col-lg-4 col-md-6';
@endphp

<footer class="site-footer">
    <div class="site-footer__main">
        <div class="container py-5">
            <div class="row g-4">
                <div class="{{ $footerColClass }}">
                    <div class="site-footer__brand mb-3">
                        @if($logoUrl)
                            <img src="{{ $logoUrl }}" alt="{{ $shopName }}" class="site-footer__logo mb-3">
                        @else
                            <div class="site-footer__title">
                                <i class="fa-solid fa-leaf me-2"></i>{{ $shopName }}
                            </div>
                        @endif
                        @if($shopDescription)
                            <p class="site-footer__text mb-0">{{ $shopDescription }}</p>
                        @endif
                    </div>
                </div>

                <div class="{{ $footerColClass }}">
                    <h6 class="site-footer__heading">Навигация</h6>
                    <ul class="site-footer__links list-unstyled mb-0">
                        <li><a href="{{ url('/catalog') }}">Каталог</a></li>
                        <li><a href="{{ url('/contacts') }}">Контакты</a></li>
                        <li><a href="{{ url('/about') }}">О компании</a></li>
                    </ul>
                </div>

                @if($hasLegalColumn)
                    <div class="{{ $footerColClass }}">
                        <h6 class="site-footer__heading">Реквизиты</h6>
                        <ul class="site-footer__legal-list list-unstyled mb-0">
                            @if($legalName)<li>{{ $legalName }}</li>@endif
                            @if($legalInn)<li>ИНН {{ $legalInn }}</li>@endif
                            @if($legalKpp)<li>КПП {{ $legalKpp }}</li>@endif
                            @if($legalOgrn)<li>ОГРН {{ $legalOgrn }}</li>@endif
                            @if($legalAddress)<li>{{ $legalAddress }}</li>@endif
                        </ul>
                    </div>
                @endif

                <div class="{{ $footerColClass }}">
                    <h6 class="site-footer__heading">Контакты</h6>
                    <ul class="site-footer__contacts list-unstyled mb-3">
                        @if($contactPhone)
                            <li>
                                <i class="fa-solid fa-phone me-2"></i>
                                <a href="tel:{{ preg_replace('/[^\d+]/', '', $contactPhone) }}">{{ $contactPhone }}</a>
                            </li>
                        @endif
                        @if($contactEmail)
                            <li>
                                <i class="fa-solid fa-envelope me-2"></i>
                                <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a>
                            </li>
                        @endif
                        @if($contactAddress)
                            <li>
                                <i class="fa-solid fa-location-dot me-2"></i>
                                <span>{{ $contactAddress }}</span>
                            </li>
                        @endif
                    </ul>

                    <div class="site-footer__social d-flex gap-2">
                        @if($socialVk)
                            <a href="{{ $socialVk }}" target="_blank" rel="noopener" class="site-footer__social-link" title="ВКонтакте">
                                <i class="fa-brands fa-vk"></i>
                            </a>
                        @endif
                        @if($socialTelegram)
                            <a href="{{ $socialTelegram }}" target="_blank" rel="noopener" class="site-footer__social-link" title="Telegram">
                                <i class="fa-brands fa-telegram"></i>
                            </a>
                        @endif
                        @if($socialWhatsapp)
                            <a href="{{ str_starts_with($socialWhatsapp, 'http') ? $socialWhatsapp : 'https://wa.me/'.preg_replace('/\D/', '', $socialWhatsapp) }}"
                               target="_blank" rel="noopener" class="site-footer__social-link" title="WhatsApp">
                                <i class="fa-brands fa-whatsapp"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="site-footer__bottom">
        <div class="container py-3">
            <div class="site-footer__copyright text-center">
                {{ $footerText }}
            </div>
        </div>
    </div>
</footer>
