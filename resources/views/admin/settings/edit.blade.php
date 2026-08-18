@extends('layouts.gentelella')

@section('title', 'Настройки магазина')
@section('page', 'settings')
@section('breadcrumb', 'Главная > Настройки')

@section('content')
    <div class="page-header">
        <div class="page-header-row">
            <div>
                <div class="page-pretitle">Конфигурация</div>
                <h1 class="page-title">Настройки магазина</h1>
            </div>
            <div class="page-actions">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline">На главную</a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Параметры сайта</div>
                <div class="card-subtitle">Общие данные, оформление, контакты и SEO без правки кода.</div>
            </div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" id="settings-form">
                @csrf
                @method('PUT')

                <div class="admin-tabs" data-admin-tabs>
                    <div class="admin-tabs-nav" role="tablist">
                        @foreach ($groupLabels as $groupKey => $groupLabel)
                            <button
                                type="button"
                                class="admin-tab{{ $loop->first ? ' active' : '' }}"
                                role="tab"
                                data-tab-target="{{ $groupKey }}"
                                aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                            >{{ $groupLabel }}</button>
                        @endforeach
                    </div>

                    <div class="admin-tab-panel active" data-tab-panel="general" role="tabpanel">
                        <div class="form-group">
                            <label class="form-label" for="shop_name">Название магазина <span class="required">*</span></label>
                            <input
                                type="text"
                                id="shop_name"
                                name="shop_name"
                                class="form-control @error('shop_name') is-invalid @enderror"
                                value="{{ old('shop_name', $settingsValues['shop_name']) }}"
                                required
                            >
                            @error('shop_name')
                                <div class="form-help" style="color: var(--danger);">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="shop_description">Описание магазина</label>
                            <textarea
                                id="shop_description"
                                name="shop_description"
                                class="form-control @error('shop_description') is-invalid @enderror"
                                rows="3"
                            >{{ old('shop_description', $settingsValues['shop_description']) }}</textarea>
                            @error('shop_description')
                                <div class="form-help" style="color: var(--danger);">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="about_text">О компании</label>
                            <div class="rich-text" data-rich-text>
                                <textarea id="about_text" name="about_text" hidden>{{ old('about_text', $settingsValues['about_text']) }}</textarea>
                            </div>
                            @error('about_text')
                                <div class="form-help" style="color: var(--danger);">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group" style="margin-bottom:0">
                            <label class="form-label" for="footer_text">Текст в футере</label>
                            <textarea
                                id="footer_text"
                                name="footer_text"
                                class="form-control @error('footer_text') is-invalid @enderror"
                                rows="2"
                            >{{ old('footer_text', $settingsValues['footer_text']) }}</textarea>
                            <div class="form-help">Отображается в нижней части сайта, под основным блоком футера.</div>
                            @error('footer_text')
                                <div class="form-help" style="color: var(--danger);">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="admin-tab-panel" data-tab-panel="logo" role="tabpanel">
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="logo">Логотип</label>
                                <div class="image-dropzone" data-image-dropzone>
                                    <input
                                        type="file"
                                        id="logo"
                                        name="logo"
                                        accept="image/jpeg,image/png,image/webp,image/gif,image/svg+xml"
                                    >
                                    <div class="image-dropzone-preview" data-dropzone-preview>
                                        @if ($settingsValues['logo'])
                                            <img src="{{ asset('storage/'.$settingsValues['logo']) }}" alt="Логотип">
                                        @endif
                                    </div>
                                    <div class="image-dropzone-empty">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                            <path d="M12 16V4M12 4l-4 4M12 4l4 4" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        <p style="margin:0">Перетащите логотип сюда или</p>
                                        <button type="button" class="btn btn-outline btn-sm" data-dropzone-browse>Выбрать файл</button>
                                    </div>
                                    <button type="button" class="image-dropzone-remove" data-dropzone-remove aria-label="Убрать логотип">×</button>
                                </div>
                                <div class="form-help">Рекомендуемый размер: до 400×200 px. Используется в шапке и футере сайта.</div>
                                @error('logo')
                                    <div class="form-help" style="color: var(--danger);">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="favicon">Favicon</label>
                                <div class="image-dropzone" data-image-dropzone>
                                    <input
                                        type="file"
                                        id="favicon"
                                        name="favicon"
                                        accept="image/jpeg,image/png,image/webp,image/gif,image/x-icon"
                                    >
                                    <div class="image-dropzone-preview" data-dropzone-preview>
                                        @if ($settingsValues['favicon'])
                                            <img src="{{ asset('storage/'.$settingsValues['favicon']) }}" alt="Favicon">
                                        @endif
                                    </div>
                                    <div class="image-dropzone-empty">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                                            <path d="M12 16V4M12 4l-4 4M12 4l4 4" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        <p style="margin:0">Перетащите favicon сюда или</p>
                                        <button type="button" class="btn btn-outline btn-sm" data-dropzone-browse>Выбрать файл</button>
                                    </div>
                                    <button type="button" class="image-dropzone-remove" data-dropzone-remove aria-label="Убрать favicon">×</button>
                                </div>
                                <div class="form-help">Рекомендуемый размер: 64×64 px.</div>
                                @error('favicon')
                                    <div class="form-help" style="color: var(--danger);">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="admin-tab-panel" data-tab-panel="social" role="tabpanel">
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="social_vk">ВКонтакте</label>
                                <input
                                    type="url"
                                    id="social_vk"
                                    name="social_vk"
                                    class="form-control @error('social_vk') is-invalid @enderror"
                                    value="{{ old('social_vk', $settingsValues['social_vk']) }}"
                                    placeholder="https://vk.com/..."
                                >
                                @error('social_vk')
                                    <div class="form-help" style="color: var(--danger);">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="social_telegram">Telegram</label>
                                <input
                                    type="url"
                                    id="social_telegram"
                                    name="social_telegram"
                                    class="form-control @error('social_telegram') is-invalid @enderror"
                                    value="{{ old('social_telegram', $settingsValues['social_telegram']) }}"
                                    placeholder="https://t.me/..."
                                >
                                @error('social_telegram')
                                    <div class="form-help" style="color: var(--danger);">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom:0">
                            <label class="form-label" for="social_whatsapp">WhatsApp</label>
                            <input
                                type="url"
                                id="social_whatsapp"
                                name="social_whatsapp"
                                class="form-control @error('social_whatsapp') is-invalid @enderror"
                                value="{{ old('social_whatsapp', $settingsValues['social_whatsapp']) }}"
                                placeholder="https://wa.me/..."
                            >
                            @error('social_whatsapp')
                                <div class="form-help" style="color: var(--danger);">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="admin-tab-panel" data-tab-panel="benefits" role="tabpanel">
                        <div class="form-group" style="margin-bottom:0">
                            <label class="form-label" for="benefits">Карусель преимуществ (JSON)</label>
                            <textarea
                                id="benefits"
                                name="benefits"
                                class="form-control @error('benefits') is-invalid @enderror"
                                rows="10"
                                style="font-family: monospace; font-size: 13px;"
                            >{{ old('benefits', $settingsValues['benefits']) }}</textarea>
                            <div class="form-help">Массив объектов: icon, title, text. Отображается блоком преимуществ на главной странице.</div>
                            @error('benefits')
                                <div class="form-help" style="color: var(--danger);">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="admin-tab-panel" data-tab-panel="contacts" role="tabpanel">
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="contact_phone">Телефон</label>
                                <input
                                    type="text"
                                    id="contact_phone"
                                    name="contact_phone"
                                    class="form-control @error('contact_phone') is-invalid @enderror"
                                    value="{{ old('contact_phone', $settingsValues['contact_phone']) }}"
                                >
                                @error('contact_phone')
                                    <div class="form-help" style="color: var(--danger);">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="contact_email">Email</label>
                                <input
                                    type="email"
                                    id="contact_email"
                                    name="contact_email"
                                    class="form-control @error('contact_email') is-invalid @enderror"
                                    value="{{ old('contact_email', $settingsValues['contact_email']) }}"
                                >
                                @error('contact_email')
                                    <div class="form-help" style="color: var(--danger);">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="contact_address">Адрес</label>
                            <textarea
                                id="contact_address"
                                name="contact_address"
                                class="form-control @error('contact_address') is-invalid @enderror"
                                rows="2"
                            >{{ old('contact_address', $settingsValues['contact_address']) }}</textarea>
                            @error('contact_address')
                                <div class="form-help" style="color: var(--danger);">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group" style="margin-bottom:0">
                            <label class="form-label" for="contact_map_iframe">Карта (iframe-код)</label>
                            <textarea
                                id="contact_map_iframe"
                                name="contact_map_iframe"
                                class="form-control @error('contact_map_iframe') is-invalid @enderror"
                                rows="5"
                                placeholder='<iframe src="..." ...></iframe>'
                            >{{ old('contact_map_iframe', $settingsValues['contact_map_iframe']) }}</textarea>
                            <div class="form-help">Вставьте HTML-код iframe из Яндекс.Карт или Google Maps.</div>
                            @error('contact_map_iframe')
                                <div class="form-help" style="color: var(--danger);">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="admin-tab-panel" data-tab-panel="home" role="tabpanel">
                        <div class="form-group">
                            <label class="form-label" for="seo_home_title">Meta Title главной страницы</label>
                            <input
                                type="text"
                                id="seo_home_title"
                                name="seo_home_title"
                                class="form-control @error('seo_home_title') is-invalid @enderror"
                                value="{{ old('seo_home_title', $settingsValues['seo_home_title']) }}"
                            >
                            @error('seo_home_title')
                                <div class="form-help" style="color: var(--danger);">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="seo_home_description">Meta Description главной страницы</label>
                            <textarea
                                id="seo_home_description"
                                name="seo_home_description"
                                class="form-control @error('seo_home_description') is-invalid @enderror"
                                rows="3"
                            >{{ old('seo_home_description', $settingsValues['seo_home_description']) }}</textarea>
                            @error('seo_home_description')
                                <div class="form-help" style="color: var(--danger);">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group" style="margin-bottom:0">
                            <label class="form-label" for="seo_home_text">SEO-текст на главной</label>
                            <div class="rich-text" data-rich-text>
                                <textarea id="seo_home_text" name="seo_home_text" hidden>{{ old('seo_home_text', $settingsValues['seo_home_text']) }}</textarea>
                            </div>
                            <div class="form-help">Отображается отдельным блоком в конце главной страницы.</div>
                            @error('seo_home_text')
                                <div class="form-help" style="color: var(--danger);">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="admin-tab-panel" data-tab-panel="seo" role="tabpanel">
                        <div class="form-group">
                            <label class="form-label" for="seo_meta_title">Глобальный Meta Title</label>
                            <input
                                type="text"
                                id="seo_meta_title"
                                name="seo_meta_title"
                                class="form-control @error('seo_meta_title') is-invalid @enderror"
                                value="{{ old('seo_meta_title', $settingsValues['seo_meta_title']) }}"
                            >
                            @error('seo_meta_title')
                                <div class="form-help" style="color: var(--danger);">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="seo_meta_keywords">Ключевые слова</label>
                            <textarea
                                id="seo_meta_keywords"
                                name="seo_meta_keywords"
                                class="form-control @error('seo_meta_keywords') is-invalid @enderror"
                                rows="2"
                            >{{ old('seo_meta_keywords', $settingsValues['seo_meta_keywords']) }}</textarea>
                            @error('seo_meta_keywords')
                                <div class="form-help" style="color: var(--danger);">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group" style="margin-bottom:0">
                            <label class="form-label" for="seo_meta_description">Глобальный Meta Description</label>
                            <textarea
                                id="seo_meta_description"
                                name="seo_meta_description"
                                class="form-control @error('seo_meta_description') is-invalid @enderror"
                                rows="3"
                            >{{ old('seo_meta_description', $settingsValues['seo_meta_description']) }}</textarea>
                            @error('seo_meta_description')
                                <div class="form-help" style="color: var(--danger);">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="admin-tab-panel" data-tab-panel="legal" role="tabpanel">
                        <div class="form-group">
                            <label class="form-label" for="legal_name">Полное наименование организации</label>
                            <input
                                type="text"
                                id="legal_name"
                                name="legal_name"
                                class="form-control @error('legal_name') is-invalid @enderror"
                                value="{{ old('legal_name', $settingsValues['legal_name']) }}"
                                placeholder="ООО «ГазонМаркет» / ИП Иванов Иван Иванович"
                            >
                            @error('legal_name')
                                <div class="form-help" style="color: var(--danger);">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="legal_inn">ИНН</label>
                                <input
                                    type="text"
                                    id="legal_inn"
                                    name="legal_inn"
                                    class="form-control @error('legal_inn') is-invalid @enderror"
                                    value="{{ old('legal_inn', $settingsValues['legal_inn']) }}"
                                    placeholder="10 или 12 цифр"
                                >
                                @error('legal_inn')
                                    <div class="form-help" style="color: var(--danger);">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="legal_kpp">КПП</label>
                                <input
                                    type="text"
                                    id="legal_kpp"
                                    name="legal_kpp"
                                    class="form-control @error('legal_kpp') is-invalid @enderror"
                                    value="{{ old('legal_kpp', $settingsValues['legal_kpp']) }}"
                                    placeholder="9 цифр"
                                >
                                @error('legal_kpp')
                                    <div class="form-help" style="color: var(--danger);">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="legal_ogrn">ОГРН / ОГРНИП</label>
                                <input
                                    type="text"
                                    id="legal_ogrn"
                                    name="legal_ogrn"
                                    class="form-control @error('legal_ogrn') is-invalid @enderror"
                                    value="{{ old('legal_ogrn', $settingsValues['legal_ogrn']) }}"
                                    placeholder="13 или 15 цифр"
                                >
                                @error('legal_ogrn')
                                    <div class="form-help" style="color: var(--danger);">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom:0">
                            <label class="form-label" for="legal_address">Юридический адрес</label>
                            <textarea
                                id="legal_address"
                                name="legal_address"
                                class="form-control @error('legal_address') is-invalid @enderror"
                                rows="2"
                            >{{ old('legal_address', $settingsValues['legal_address']) }}</textarea>
                            <div class="form-help">Реквизиты отображаются в футере сайта в соответствии с требованиями законодательства РФ.</div>
                            @error('legal_address')
                                <div class="form-help" style="color: var(--danger);">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-actions right" style="margin-top: var(--gap); border-top: 1px solid var(--border-color-light); padding-top: var(--gap);">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline">Отмена</a>
                    <button type="submit" class="btn btn-primary">Сохранить настройки</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    @vite(['resources/js/admin-settings-form.js'])
@endpush
