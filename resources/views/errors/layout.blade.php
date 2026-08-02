{{-- Shared branded error shell — frontend Daraz color tokens --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>@yield('title') | {{ config('app.name', 'Marketplace') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #F85606;
            --primary-deep: #C43D00;
            --primary-tint: #FFF1EA;
            --text-main: #191919;
            --text-sub: #595959;
            --text-muted: #767676;
            --surface: #FFFFFF;
            --surface-muted: #F5F5F5;
            --border: #E5E5E5;
            --danger: #D93025;
            --warning: #B7791A;
            --success: #1D8A45;
            --info: #0F6FC5;
        }

        *, *::before, *::after { box-sizing: border-box; }

        html, body {
            margin: 0;
            min-height: 100%;
            font-family: "Noto Sans", -apple-system, BlinkMacSystemFont, Roboto, "Helvetica Neue", Arial, sans-serif;
            background: var(--surface-muted);
            color: var(--text-main);
            -webkit-font-smoothing: antialiased;
        }

        a { color: inherit; text-decoration: none; }

        .error-page {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .error-page::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 80% 50% at 50% -10%, rgba(248, 86, 6, 0.14), transparent 60%),
                radial-gradient(ellipse 50% 40% at 100% 100%, rgba(248, 86, 6, 0.08), transparent 55%);
            pointer-events: none;
        }

        .error-brand {
            position: relative;
            z-index: 1;
            margin-bottom: 1.5rem;
            font-size: 0.8125rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: var(--primary);
        }

        .error-card {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 28rem;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 2rem 1.5rem 1.75rem;
            text-align: center;
            box-shadow: 0 2px 8px rgba(25, 25, 25, 0.06);
        }

        .error-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 3.25rem;
            height: 2rem;
            padding: 0 0.75rem;
            margin-bottom: 1rem;
            border-radius: 999px;
            font-size: 0.8125rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            color: var(--primary);
            background: var(--primary-tint);
        }

        .error-badge--danger { color: var(--danger); background: #FBEBEC; }
        .error-badge--warning { color: var(--warning); background: #FFF3CD; }
        .error-badge--info { color: var(--info); background: #E8F2FC; }
        .error-badge--muted { color: var(--text-muted); background: var(--surface-muted); }

        .error-code {
            margin: 0;
            font-size: clamp(3.5rem, 12vw, 5rem);
            font-weight: 700;
            line-height: 1;
            letter-spacing: -0.04em;
            color: var(--primary);
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-deep) 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .error-code--danger {
            background: linear-gradient(135deg, #D93025 0%, #B71C1C 100%);
            -webkit-background-clip: text;
            background-clip: text;
        }

        .error-code--warning {
            background: linear-gradient(135deg, #B7791A 0%, #8C5E14 100%);
            -webkit-background-clip: text;
            background-clip: text;
        }

        .error-code--info {
            background: linear-gradient(135deg, #0F6FC5 0%, #0A5496 100%);
            -webkit-background-clip: text;
            background-clip: text;
        }

        .error-title {
            margin: 0.85rem 0 0;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-main);
            line-height: 1.3;
        }

        .error-message {
            margin: 0.6rem 0 0;
            font-size: 0.9375rem;
            font-weight: 400;
            color: var(--text-sub);
            line-height: 1.55;
        }

        .error-actions {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 0.6rem;
            margin-top: 1.5rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            min-height: 2.5rem;
            padding: 0.55rem 1.15rem;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 600;
            font-family: inherit;
            border: 1px solid transparent;
            cursor: pointer;
            transition: background-color 0.15s ease, border-color 0.15s ease, color 0.15s ease;
        }

        .btn-primary {
            background: var(--primary);
            color: #fff;
        }

        .btn-primary:hover {
            background: var(--primary-deep);
        }

        .btn-secondary {
            background: var(--surface);
            color: var(--text-main);
            border-color: var(--border);
        }

        .btn-secondary:hover {
            background: var(--primary-tint);
            border-color: #FFD5B8;
            color: var(--primary-deep);
        }

        .error-footer {
            position: relative;
            z-index: 1;
            margin-top: 1.25rem;
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .error-footer a {
            color: var(--primary-deep);
            font-weight: 600;
        }

        .error-footer a:hover {
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .error-card { padding: 1.5rem 1.15rem 1.35rem; }
            .error-actions { flex-direction: column; }
            .btn { width: 100%; }
        }
    </style>
</head>
<body>
    <main class="error-page">
        <div class="error-brand">{{ config('app.name', 'Marketplace') }}</div>

        <div class="error-card">
            @hasSection('badge')
                @yield('badge')
            @else
                <span class="error-badge">Error</span>
            @endif

            <p class="error-code @yield('code_tone')">@yield('code')</p>
            <h1 class="error-title">@yield('heading', __('Something went wrong'))</h1>
            <p class="error-message">@yield('message')</p>

            <div class="error-actions">
                @hasSection('actions')
                    @yield('actions')
                @else
                    <a href="{{ url('/') }}" class="btn btn-primary">Go to homepage</a>
                    <a href="javascript:history.back()" class="btn btn-secondary">Go back</a>
                @endif
            </div>
        </div>

        <p class="error-footer">
            Need help? <a href="{{ url('/') }}">Return to shop</a>
        </p>
    </main>
</body>
</html>
