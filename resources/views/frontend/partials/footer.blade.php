@if(($footerSetting->is_active ?? false))
    @php
        $footerGallery = array_values(array_filter($footerSetting->gallery_images ?? []));
        $footerMapUrl = trim($footerSetting->map_embed_url ?? '');

        if (str_contains($footerMapUrl, '<iframe') && preg_match('/src=["\']([^"\']+)["\']/', $footerMapUrl, $mapMatch)) {
            $footerMapUrl = $mapMatch[1];
        }

        $footerLogo = $footerSetting->logo
            ?: ((isset($home) && $home->header_logo_url) ? $home->header_logo_url : null);
    @endphp

    <footer class="site-footer" style="--footer-bg:{{ $footerSetting->background_color ?: '#2756a5' }};--footer-bottom:{{ $footerSetting->bottom_bar_color ?: '#064f80' }}">
        <div class="site-footer-main">
            <div class="container">
                <div class="site-footer-grid">
                    <section class="site-footer-brand">
                        @if($footerLogo)
                            <img src="{{ asset($footerLogo) }}" alt="KASBIT" class="site-footer-logo">
                        @else
                            <h2>KASBIT</h2>
                        @endif

                        @foreach([$footerSetting->address_1, $footerSetting->address_2, $footerSetting->address_3] as $address)
                            @if($address)
                                <p class="site-footer-address">{{ $address }}</p>
                            @endif
                        @endforeach

                        <div class="site-footer-socials">
                            @foreach([
                                ['url' => $footerSetting->facebook_url, 'icon' => 'fa-brands fa-facebook-f', 'label' => 'Facebook'],
                                ['url' => $footerSetting->instagram_url, 'icon' => 'fa-brands fa-instagram', 'label' => 'Instagram'],
                                ['url' => $footerSetting->linkedin_url, 'icon' => 'fa-brands fa-linkedin-in', 'label' => 'LinkedIn'],
                            ] as $social)
                                @if($social['url'])
                                    <a href="{{ $social['url'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $social['label'] }}">
                                        <i class="{{ $social['icon'] }}"></i>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </section>

                    <section class="site-footer-middle">
                        @if(count($footerGallery))
                            <div class="site-footer-gallery-wrap">
                                <h3>Gallery</h3>
                                <span class="site-footer-heading-line"></span>
                                <div class="site-footer-gallery">
                                    @foreach($footerGallery as $image)
                                        <a href="{{ asset($image) }}" target="_blank" rel="noopener noreferrer">
                                            <img src="{{ asset($image) }}" alt="KASBIT gallery image">
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </section>

                    <section class="site-footer-map">
                        <h3>{{ $footerSetting->map_title ?: 'Location Map' }}</h3>
                        <span class="site-footer-heading-line"></span>
                        @if($footerMapUrl)
                            <div class="site-footer-map-frame">
                                <iframe src="{{ $footerMapUrl }}" title="{{ $footerSetting->map_title ?: 'Location Map' }}" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
                            </div>
                        @endif
                    </section>
                </div>
            </div>
        </div>

        <div class="site-footer-bottom">
            <div class="container">
                {{ $footerSetting->copyright_text }}
            </div>
        </div>
    </footer>
@endif
