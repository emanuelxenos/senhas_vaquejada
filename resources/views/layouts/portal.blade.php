<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ \App\Models\Setting::getValue('parque.name', 'Portal do Vaqueiro') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    
    <style>
        :root {
            /* Premium Rustic Vaquejada & Sunset Theme */
            --primary: #d97706;       /* Sunset Gold */
            --primary-glow: rgba(217, 119, 6, 0.35);
            --primary-dark: #b45309;
            --accent: #fbbf24;        /* Sand Gold */
            --bg-base: #050200;       /* Dark Earth/Clay */
            
            --glass-bg: rgba(24, 10, 2, 0.65);
            --glass-border: rgba(217, 119, 6, 0.18);
            --glass-shadow: 0 12px 40px 0 rgba(0, 0, 0, 0.6);
            
            --text-main: #fffbeb;     /* Sand text */
            --text-muted: #fed7aa;    /* Sunset light text */
            --danger: #ef4444;
            --warning: #f59e0b;
            --success: #10b981;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(185deg, rgba(24, 10, 2, 0.94) 0%, rgba(5, 2, 0, 0.97) 100%), url('/vaquejada_bg.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-family: 'Inter', sans-serif;
            position: relative;
            overflow-x: hidden;
        }

        /* Efeito de Poeira/Sunset Dourado na Arena */
        .dust-overlay {
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(217, 119, 6, 0.02) 1px, transparent 0);
            background-size: 32px 32px;
            z-index: -1;
            pointer-events: none;
        }

        /* Brilho suave no topo */
        body::before {
            content: '';
            position: absolute;
            top: -10%;
            left: 50%;
            transform: translateX(-50%);
            width: 800px;
            height: 400px;
            background: radial-gradient(circle, rgba(217, 119, 6, 0.12) 0%, transparent 70%);
            filter: blur(80px);
            z-index: -1;
            pointer-events: none;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Outfit', sans-serif;
            letter-spacing: -0.02em;
        }

        .navbar {
            background: rgba(2, 6, 23, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            padding: 1.2rem 2rem;
            border-bottom: 1px solid var(--glass-border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .navbar-brand {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 1.5rem;
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            letter-spacing: -0.03em;
        }

        .navbar-brand span {
            background: linear-gradient(135deg, var(--primary), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .navbar-nav {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        .navbar-nav a, .navbar-nav button {
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 500;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 0.95rem;
            transition: color 0.3s ease;
            font-family: 'Inter', sans-serif;
        }

        .navbar-nav a:hover, .navbar-nav button:hover {
            color: #fff;
        }

        .container {
            flex: 1;
            padding: 2rem;
            max-width: 900px;
            margin: 0 auto;
            width: 100%;
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
            transform: translateY(20px);
        }

        .card {
            background: var(--glass-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: 20px;
            padding: 2rem;
            border: 1px solid var(--glass-border);
            box-shadow: var(--glass-shadow);
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px 0 rgba(0, 0, 0, 0.45);
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
        }

        .card-header {
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .card-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 0.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-muted);
        }

        .form-control {
            width: 100%;
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid var(--glass-border);
            color: #fff;
            padding: 0.875rem 1.25rem;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            font-family: 'Inter', sans-serif;
        }

        .form-control:focus {
            outline: none;
            background: rgba(15, 23, 42, 0.8);
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-glow);
        }

        .form-control::placeholder {
            color: rgba(148, 163, 184, 0.5);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.875rem 1.75rem;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            width: 100%;
            font-family: 'Outfit', sans-serif;
            font-size: 1.05rem;
            position: relative;
            overflow: hidden;
        }

        .btn::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 150%;
            height: 150%;
            background: rgba(255,255,255,0.1);
            transform: translate(-50%, -50%) scale(0);
            border-radius: 50%;
            transition: transform 0.5s ease;
        }

        .btn:active::after {
            transform: translate(-50%, -50%) scale(1);
            transition: 0s;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff;
            box-shadow: 0 4px 15px var(--primary-glow);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px var(--primary-glow);
            background: linear-gradient(135deg, #34d399, var(--primary));
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.05);
            color: #fff;
            border: 1px solid var(--glass-border);
        }
        
        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .alert {
            padding: 1rem 1.5rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            backdrop-filter: blur(10px);
        }
        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            color: #fca5a5;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }
        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            color: #6ee7b7;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.35rem 0.75rem;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            backdrop-filter: blur(4px);
        }
        .badge-pendente { background: rgba(245, 158, 11, 0.15); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.3); }
        .badge-pago { background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); }
        .badge-cancelado { background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); }

        .text-center { text-align: center; }
        .mt-4 { margin-top: 1rem; }
        .mt-6 { margin-top: 1.5rem; }
        .mb-4 { margin-bottom: 1rem; }

        @keyframes slideUp {
            to { opacity: 1; transform: translateY(0); }
        }

        /* Utils */
        .flex { display: flex; }
        .flex-col { flex-direction: column; }
        .justify-between { justify-content: space-between; }
        .items-center { align-items: center; }
        .gap-2 { gap: 0.5rem; }
        .gap-4 { gap: 1rem; }
        .text-sm { font-size: 0.875rem; }
        .text-muted { color: var(--text-muted); }
        .hidden { display: none; }
        
        .glow-text {
            color: #fff;
            text-shadow: 0 0 20px rgba(255,255,255,0.3);
        }

        /* Mobile Adjustments */
        @media (max-width: 640px) {
            .container { padding: 1rem; }
            .card { padding: 1.5rem; }
            .navbar { padding: 1rem; }
            .navbar-brand { font-size: 1.2rem; }
            .flex-mobile-col { flex-direction: column; }
        }
    </style>
</head>
<body>
    <div class="dust-overlay"></div>

    <nav class="navbar">
        <a href="{{ route('portal.dashboard') }}" class="navbar-brand">
            @php $logo = \App\Models\Setting::getValue('parque.logo') @endphp
            @if(!empty($logo))
                <img src="{{ asset($logo) }}" alt="Logo" style="max-height: 40px; width: auto; border-radius: 6px;">
            @else
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--primary)"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path><line x1="4" x2="4" y1="22" y2="15"></line></svg>
            @endif
            <span>{{ \App\Models\Setting::getValue('parque.name', 'Portal do Vaqueiro') }}</span>
        </a>
        
        <div class="navbar-nav">
            @auth
                <form method="POST" action="{{ route('portal.logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" style="display: flex; align-items: center; gap: 0.5rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                        Sair
                    </button>
                </form>
            @else
                <a href="{{ route('portal.login') }}">Entrar</a>
            @endauth
        </div>
    </nav>

    <main class="container">
        @if (session('sucesso'))
            <div class="alert alert-success">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                {{ session('sucesso') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-error">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <footer style="text-align: center; padding: 2rem 1.5rem; margin-top: auto; border-top: 1px solid var(--glass-border); background: rgba(5, 2, 0, 0.6); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);">
        <p style="font-size: 0.85rem; color: var(--text-muted); font-family: 'Inter', sans-serif;">
            &copy; {{ date('Y') }} {{ \App\Models\Setting::getValue('parque.name', 'Portal do Vaqueiro') }}. Todos os direitos reservados.
        </p>
        <p style="font-size: 0.75rem; margin-top: 0.5rem; color: rgba(253, 224, 71, 0.6); font-family: 'Inter', sans-serif;">
            Desenvolvido por <a href="https://instagram.com/emanuelxenos" target="_blank" style="color: var(--accent); text-decoration: none; font-weight: 600; transition: color 0.3s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='var(--accent)'">Emanuel Xenos</a>
        </p>
    </footer>

    @stack('scripts')
</body>
</html>
