<!DOCTYPE html>
<html lang="en">

@php $settings = settings(); @endphp

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset($settings->favicon) }}">
    @vite('resources/css/app.css')
    <script src="{{ asset('assets/libs/jquery/jquery-3.7.1.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/frontend/styles/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/frontend/styles/responsive.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <title>Become a Seller | {{ $settings->app_name }}</title>
    <style>
        :root {
            /* Semantic design tokens */
            --color-brand-primary: #F85606;
            --color-brand-primary-deep: #C43D00;
            --color-brand-primary-tint: #FFF1EA;
            --color-text-primary: #191919;
            --color-text-secondary: #595959;
            --color-text-tertiary: #767676;
            --color-text-inverse: #FFFFFF;
            --color-surface-base: #FFFFFF;
            --color-surface-muted: #F5F5F5;
            --color-surface-strong: #191919;
            --color-border-default: #E5E5E5;
            --color-border-strong: #C7C7C7;
            --color-feedback-success: #1D8A45;
            --color-feedback-danger: #D93025;
            --color-feedback-danger-tint: #FDECEB;
            --color-feedback-warning: #B7791A;

            --font-family-primary: 'Noto Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;

            --space-2: 4px;  --space-3: 6px;  --space-4: 8px;
            --space-5: 12px; --space-6: 16px; --space-7: 20px;
            --space-8: 24px; --space-9: 32px;

            --radius-xs: 4px; --radius-sm: 8px; --radius-md: 12px; --radius-lg: 20px;

            --shadow-card: 0 1px 2px rgba(25,25,25,0.06);
            --shadow-raised: 0 8px 24px rgba(25,25,25,0.10);
            --shadow-focus: 0 0 0 3px rgba(248,86,6,0.12);

            --motion-fast: 160ms;
            --motion-standard: 240ms;
            --motion-easing: cubic-bezier(0.2, 0, 0, 1);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: var(--font-family-primary);
            background: var(--color-surface-base);
            color: var(--color-text-primary);
            min-height: 100vh;
            font-size: 14px;
            line-height: 1.5;
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.001ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.001ms !important;
                scroll-behavior: auto !important;
            }
        }

        a { color: inherit; }

        .skip-link {
            position: absolute; left: -9999px; top: 0; z-index: 999;
            background: var(--color-surface-strong); color: #fff;
            padding: var(--space-4) var(--space-6); border-radius: var(--radius-xs);
        }
        .skip-link:focus { left: var(--space-4); top: var(--space-4); }

        .seller-wrapper {
            width: 100%;
            display: flex;
            min-height: 100vh;
            max-height: 100vh;
        }

        /* ---------- LEFT PANEL ---------- */
        .seller-left {
            flex: 1 1 60%;
            background: var(--color-surface-strong);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: var(--space-9) 4rem;
            position: relative;
            overflow: hidden;
        }

        /* quiet structural texture, not decoration for its own sake */
        .seller-left::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(var(--color-brand-primary) 1px, transparent 1px),
                linear-gradient(90deg, var(--color-brand-primary) 1px, transparent 1px);
            background-size: 64px 64px;
            opacity: 0.05;
            pointer-events: none;
        }

        .seller-left-content {
            position: relative;
            z-index: 1;
            color: #fff;
            max-width: 400px;
        }

        .brand-logo {
            display: inline-block;
            margin-bottom: var(--space-9);
            text-decoration: none;
        }
        .brand-logo img { height: 32px; filter: brightness(0) invert(1); }
        .brand-logo .brand-fallback { font-size: 1.375rem; font-weight: 800; letter-spacing: -0.02em; }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: var(--space-3);
            background: rgba(248,86,6,0.16);
            color: #FF9556;
            border: 1px solid rgba(248,86,6,0.32);
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            padding: var(--space-3) var(--space-5);
            border-radius: 999px;
            margin-bottom: var(--space-7);
        }
        .eyebrow .dot { width: 6px; height: 6px; border-radius: 50%; background: var(--color-brand-primary); }

        .seller-left-content h2 {
            font-size: 2rem;
            font-weight: 800;
            line-height: 1.2;
            letter-spacing: -0.02em;
            margin-bottom: var(--space-5);
        }
        .seller-left-content > p.lede {
            font-size: 0.9375rem;
            line-height: 1.65;
            color: rgba(255,255,255,0.68);
            margin-bottom: var(--space-9);
        }

        /* ---- Signature element: seller snapshot preview ---- */
        .snapshot-card {
            background: #fff;
            border-radius: var(--radius-md);
            padding: var(--space-7);
            box-shadow: var(--shadow-raised);
            color: var(--color-text-primary);
            margin-bottom: var(--space-9);
            clip-path: polygon(0 0, calc(100% - 18px) 0, 100% 18px, 100% 100%, 0 100%);
        }
        .snapshot-card__top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: var(--space-6);
        }
        .snapshot-card__label {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--color-text-tertiary);
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        .snapshot-card__live {
            display: inline-flex;
            align-items: center;
            gap: var(--space-2);
            font-size: 0.6875rem;
            font-weight: 700;
            color: var(--color-feedback-success);
        }
        .snapshot-card__live .pulse {
            width: 6px; height: 6px; border-radius: 50%;
            background: var(--color-feedback-success);
        }
        .snapshot-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: var(--space-6);
        }
        .snapshot-stat__value {
            font-size: 1.375rem;
            font-weight: 800;
            font-variant-numeric: tabular-nums;
            letter-spacing: -0.01em;
        }
        .snapshot-stat__label {
            font-size: 0.6875rem;
            color: var(--color-text-tertiary);
            margin-top: var(--space-2);
        }
        .snapshot-stat--up { color: var(--color-feedback-success); }

        .benefits {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: var(--space-5);
        }
        .benefits li {
            display: flex;
            align-items: center;
            gap: var(--space-5);
            font-size: 0.875rem;
            color: rgba(255,255,255,0.85);
        }
        .benefits li .icon-wrap {
            width: 32px; height: 32px;
            border-radius: var(--radius-sm);
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.12);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .benefits li svg { width: 16px; height: 16px; color: var(--color-brand-primary); }

        .login-link {
            margin-top: var(--space-9);
            padding-top: var(--space-7);
            border-top: 1px solid rgba(255,255,255,0.12);
            font-size: 0.8125rem;
            color: rgba(255,255,255,0.68);
        }
        .login-link a {
            color: #fff;
            font-weight: 700;
            text-decoration: underline;
            text-underline-offset: 3px;
            text-decoration-color: rgba(255,255,255,0.4);
        }
        .login-link a:hover { text-decoration-color: #fff; }
        .login-link a:focus-visible { outline: 2px solid var(--color-brand-primary); outline-offset: 3px; border-radius: 2px; }

        /* ---------- RIGHT PANEL ---------- */
        .seller-right {
            flex: 1 1 40%;            background: #fff;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        *:focus-visible {
            outline: 2px solid var(--color-brand-primary-deep);
            outline-offset: 2px;
        }

        @media (max-width: 960px) {
            .seller-left { flex: none; padding: var(--space-9) var(--space-7); }
            .snapshot-card { margin-bottom: var(--space-7); }
            .benefits { display: none; }
            .login-link { margin-top: var(--space-6); padding-top: var(--space-6); }
            .seller-left-content h2 { font-size: 1.5rem; }
        }
        @media (max-width: 640px) {
            .seller-wrapper { flex-direction: column; max-height: none; }
            .seller-left { padding: var(--space-7) var(--space-6); }
            .snapshot-stats { gap: var(--space-4); }
            .snapshot-stat__value { font-size: 1.125rem; }
            .eyebrow { margin-bottom: var(--space-5); }
        }
    </style>
</head>

<body>
    <a href="#seller-form" class="skip-link">Skip to form</a>
    <div class="seller-wrapper">
        <div class="seller-left">
            <div class="seller-left-content">
                <a href="{{ url('/') }}" class="brand-logo">
                    @if (! empty($settings?->logo_white))
                        <img src="{{ storage_url($settings->logo_white) }}" alt="{{ app_name() }}">
                    @else
                        <span class="brand-fallback">{{ app_name() }}</span>
                    @endif
                </a>

                <span class="eyebrow"><span class="dot"></span> Open for new sellers</span>

                <h2>Start selling on {{ app_name() }} today</h2>
                <p class="lede">Set up your shop in minutes and reach buyers across the country from day one.</p>

                <div class="snapshot-card" aria-hidden="true">
                    <div class="snapshot-card__top">
                        <span class="snapshot-card__label">Your Shop</span>
                        <span class="snapshot-card__live"><span class="pulse"></span> Live</span>
                    </div>
                    <div class="snapshot-stats">
                        <div>
                            <div class="snapshot-stat__value">24</div>
                            <div class="snapshot-stat__label">Orders today</div>
                        </div>
                        <div>
                            <div class="snapshot-stat__value snapshot-stat--up">৳12.4k</div>
                            <div class="snapshot-stat__label">Revenue</div>
                        </div>
                        <div>
                            <div class="snapshot-stat__value">4.8</div>
                            <div class="snapshot-stat__label">Rating</div>
                        </div>
                    </div>
                </div>

                <ul class="benefits">
                    <li>
                        <span class="icon-wrap">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </span>
                        Reach millions of active buyers
                    </li>
                    <li>
                        <span class="icon-wrap">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </span>
                        Zero setup fees &amp; low commission
                    </li>
                    <li>
                        <span class="icon-wrap">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </span>
                        Secure payments &amp; easy withdrawals
                    </li>
                </ul>

                <div class="login-link">
                    Already have an account? <a href="{{ route('login') }}">Sign in</a>
                </div>
            </div>
        </div>

        <div class="seller-right">
            @yield('content')
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });
        });
    </script>
    @vite('resources/js/app.js')
    @stack('scripts')
</body>
</html>