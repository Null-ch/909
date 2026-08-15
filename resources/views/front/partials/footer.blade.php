<footer class="site-footer">
    <div class="site-footer__main">
        <div class="container py-5">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
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

                <div class="col-lg-4 col-md-6">
                    <h6 class="site-footer__heading">Каталог</h6>
                    <ul class="site-footer__links list-unstyled mb-0">
                        <li><a href="{{ url('/catalog') }}">Все товары</a></li>
                        @foreach($navCategories->take(5) as $category)
                            <li>
                                <a href="{{ url('/category/'.$category->slug) }}">{{ $category->name }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="col-lg-4 col-md-12">
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
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div class="site-footer__copyright">
                    {{ $footerText }}
                </div>
                <div class="site-footer__bottom-links">
                    <a href="{{ url('/page/about') }}">О компании</a>
                    <span class="mx-2">·</span>
                    <a href="{{ url('/page/delivery') }}">Доставка</a>
                </div>
            </div>
        </div>
    </div>
</footer>
