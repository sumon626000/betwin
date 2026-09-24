<footer class="site-footer">
    <div class="site-footer__glow" aria-hidden="true"></div>

    <div class="site-footer__inner">
        <div class="site-footer__brand">
            <div class="site-footer__logo">
                <img src="{{ asset('assets/images/logo_icon/favicon.png') }}" alt="{{ __(gs('site_name')) }}">
            </div>
            <div class="site-footer__meta">
                <p class="site-footer__copy">
                    BET369WIN website is operated by company, under license number GLH-OCCHKTW07080120 issued to it and regulated by Gaming Services Provider N.V., authorized by the Government of Curaçao under license number 365JAZ.
                </p>
                <div class="site-footer__badges">
                    <span class="site-footer__age" title="18+ only">18+</span>
                </div>
            </div>
        </div>

        <div class="site-footer__social" aria-label="BET369WIN contact">
            <a href="https://t.me/bet369win" target="_blank" rel="noopener noreferrer" title="Chat" aria-label="Chat">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.5 3.5A11 11 0 0 0 2.1 17.6L1 23l5.6-1.5A11 11 0 1 0 20.5 3.5zm-8.6 17a9.1 9.1 0 0 1-4.6-1.3l-.3-.2-3.3.9.9-3.2-.2-.3a9.1 9.1 0 1 1 7.5 4.1zm5-6.8c-.3-.1-1.6-.8-1.9-.9s-.4-.1-.6.1l-.8 1c-.2.2-.3.2-.6.1a7.4 7.4 0 0 1-2.2-1.4 8.2 8.2 0 0 1-1.5-1.9c-.2-.3 0-.4.1-.6l.5-.6c.1-.2.2-.3.3-.5s0-.4 0-.5l-.9-2.1c-.2-.5-.5-.5-.6-.5h-.5c-.2 0-.5.1-.7.3a2.4 2.4 0 0 0-.8 1.8 4.2 4.2 0 0 0 .9 2.2 9.6 9.6 0 0 0 3.7 3.5 12 12 0 0 0 1.4.5 3.3 3.3 0 0 0 1.5.1 2.5 2.5 0 0 0 1.7-1.1 2 2 0 0 0 .1-1.1c-.1-.1-.2-.2-.5-.3z"/></svg>
            </a>
            <a href="https://www.facebook.com/bet369win" target="_blank" rel="noopener noreferrer" title="Facebook" aria-label="Facebook">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 9h3V6h-3c-2.2 0-4 1.8-4 4v2H7v3h3v7h3v-7h3l1-3h-4v-2c0-.6.4-1 1-1z"/></svg>
            </a>
            <a href="https://t.me/bet369win" target="_blank" rel="noopener noreferrer" title="Telegram" aria-label="Telegram">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21.5 3.3 2.9 10.5c-1.3.5-1.2 1.2-.2 1.5l4.8 1.5 1.8 5.6c.2.7.1.9.8.9.5 0 .7-.2 1-.5l2.4-2.3 5 3.7c.9.5 1.6.2 1.8-.9L23 4.5c.3-1.2-.4-1.7-1.5-1.2zM8.9 13.7l9.5-6c.4-.2.7 0 .4.2l-7.7 7-.3 3.3-1.9-4.5z"/></svg>
            </a>
            <a class="is-support" href="{{ auth()->check() ? route('ticket.index') : route('contact') }}" title="Support" aria-label="Support">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 12a8 8 0 0 1 16 0"/><path d="M4 12v2a3 3 0 0 0 3 3h1v-5H4zM20 12v2a3 3 0 0 1-3 3h-1v-5h4z"/><path d="M12 19v2"/><path d="M9 21h6"/></svg>
            </a>
        </div>
    </div>

    <div class="site-footer__partners">
        {{-- Logos rendered above via provider_logos partial --}}
    </div>
</footer>
