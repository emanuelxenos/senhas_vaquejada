<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ \App\Models\Setting::getValue('parque.name', 'Parque de Vaquejada') }} - Arena Oficial</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@700;800;900&family=Playfair+Display:ital,wght@0,600;0,700;1,400&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-arena: linear-gradient(185deg, #180a02 0%, #050200 100%);
            --gold: #d97706;
            --gold-light: #fbbf24;
            --gold-glow: rgba(217, 119, 6, 0.15);
            --sand: #ffedd5;
            --clay: #ea580c;
            --glass-bg: rgba(24, 10, 2, 0.65);
            --glass-border: rgba(217, 119, 6, 0.18);
            --text-gold: #fde047;
            --text-light: #fffbeb;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(185deg, rgba(24, 10, 2, 0.92) 0%, rgba(5, 2, 0, 0.96) 100%), url('/vaquejada_bg.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed; /* Efeito Parallax Rústico */
            color: var(--text-light);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow-x: hidden;
            position: relative;
        }

        /* Efeito de Poeira/Sunset Dourado na Arena */
        .dust-overlay {
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(217, 119, 6, 0.02) 1px, transparent 0);
            background-size: 32px 32px;
            z-index: 1;
            pointer-events: none;
        }

        /* Brilho da porteira de entrada */
        body::before {
            content: "";
            position: absolute;
            top: -15%;
            left: 50%;
            transform: translateX(-50%);
            width: 900px;
            height: 500px;
            background: radial-gradient(circle, rgba(217, 119, 6, 0.1) 0%, rgba(0,0,0,0) 70%);
            z-index: 0;
            pointer-events: none;
            filter: blur(80px);
        }

        .container {
            width: 100%;
            max-width: 960px;
            margin: 0 auto;
            padding: 4rem 1.5rem;
            z-index: 10;
            position: relative;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        header {
            text-align: center;
            margin-bottom: 3.5rem;
        }

        .welcome-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(217, 119, 6, 0.08);
            border: 1px solid rgba(217, 119, 6, 0.25);
            color: var(--text-gold);
            padding: 0.5rem 1.5rem;
            border-radius: 4px;
            margin-bottom: 1.5rem;
            font-weight: 700;
            font-size: 0.8rem;
            letter-spacing: 3px;
            text-transform: uppercase;
            border-top: 2px solid var(--gold);
            text-shadow: 0 2px 4px rgba(0,0,0,0.5);
        }

        h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 4rem;
            font-weight: 900;
            line-height: 1.05;
            letter-spacing: -0.5px;
            margin-bottom: 1rem;
            text-transform: uppercase;
            text-shadow: 0 4px 20px rgba(0,0,0,0.8);
            background: linear-gradient(135deg, #ffffff 40%, #ffedd5 70%, #d97706 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .tagline {
            font-family: 'Playfair Display', serif;
            font-size: 1.45rem;
            font-style: italic;
            color: #fed7aa;
            margin-bottom: 2.5rem;
            text-shadow: 0 2px 5px rgba(0,0,0,0.5);
        }

        /* Manifesto de Emoção do Vaqueiro */
        .manifesto {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 3rem;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            margin-bottom: 3rem;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.5);
            position: relative;
            text-align: center;
        }

        /* Detalhe de bordas rústicas nos cantos */
        .manifesto::before {
            content: "✦";
            position: absolute;
            top: 15px;
            left: 50%;
            transform: translateX(-50%);
            color: var(--gold);
            font-size: 1.2rem;
            opacity: 0.6;
        }

        .poetry {
            font-family: 'Playfair Display', serif;
            font-size: 1.25rem;
            line-height: 1.8;
            color: #ffedd5;
            margin-bottom: 2rem;
            font-style: italic;
        }

        .highlight-box {
            border-top: 1px solid rgba(217, 119, 6, 0.15);
            padding-top: 2rem;
            margin-top: 2rem;
        }

        .highlight-box h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.8rem;
            font-weight: 800;
            color: #fff;
            text-transform: uppercase;
            margin-bottom: 0.75rem;
            letter-spacing: 0.5px;
        }

        .highlight-box p {
            color: #fed7aa;
            font-size: 1.05rem;
            line-height: 1.6;
            max-width: 720px;
            margin: 0 auto 2.5rem;
        }

        /* Botões de Chamada para Ação Grandes e Imponentes */
        .cta-group {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            max-width: 650px;
            margin: 0 auto;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 1.25rem 2rem;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            cursor: pointer;
            gap: 0.6rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }

        .btn-gold {
            background: linear-gradient(to bottom, #f59e0b, #d97706);
            color: #180a02;
            border-bottom: 4px solid #b45309;
        }

        .btn-gold:hover {
            background: linear-gradient(to bottom, #fbbf24, #f59e0b);
            transform: translateY(-2px);
            box-shadow: 0 10px 30px var(--gold-glow);
        }

        .btn-outline {
            background: rgba(255,255,255,0.03);
            border: 2px solid var(--glass-border);
            color: #fff;
            border-bottom: 4px solid var(--glass-border);
        }

        .btn-outline:hover {
            background: rgba(217, 119, 6, 0.08);
            border-color: var(--gold);
            transform: translateY(-2px);
        }

        footer.site-footer {
            text-align: center;
            padding: 2rem 1.5rem;
            font-size: 0.85rem;
            color: rgba(255,255,255,0.2);
            border-top: 1px solid rgba(255,255,255,0.02);
            background: rgba(10, 4, 1, 0.9);
            z-index: 10;
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 960px;
            margin: 0 auto;
            width: 100%;
        }

        footer.site-footer a.admin-link {
            color: rgba(255,255,255,0.1);
            text-decoration: none;
            transition: color 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        footer.site-footer a.admin-link:hover {
            color: var(--gold);
        }

        /* Responsividade */
        @media (max-width: 768px) {
            h1 {
                font-size: 2.75rem;
            }
            .tagline {
                font-size: 1.15rem;
            }
            .manifesto {
                padding: 2rem 1.5rem;
            }
            .poetry {
                font-size: 1.1rem;
            }
            .cta-group {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            footer.site-footer {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
        }
    </style>
</head>
<body>

    <div class="dust-overlay"></div>

    <div class="container">
        
        <header>
            <div class="welcome-badge">
                Aporte de Tradição & Paixão
            </div>
            <h1>{{ \App\Models\Setting::getValue('parque.name', 'Parque de Vaquejada') }}</h1>
            <p class="tagline">Onde a poeira sobe e a tradição do vaqueiro se torna eterna.</p>
        </header>

        @php
            $cronograma = \App\Models\Setting::getValue('parque.cronograma');
        @endphp

        @if(!empty($cronograma))
            <!-- Cronograma / Programação Oficial -->
            <div class="manifesto" style="margin-bottom: 2.5rem; border-top: 2px solid var(--gold);">
                <h2 style="font-family: 'Outfit', sans-serif; font-size: 2rem; font-weight: 800; color: #fff; text-transform: uppercase; margin-bottom: 1.5rem; letter-spacing: 1px; display: flex; align-items: center; justify-content: center; gap: 10px;">
                    <i class="far fa-calendar-alt text-warning"></i>
                    Programação Oficial
                </h2>
                <div style="text-align: left; color: #ffedd5; line-height: 1.8; font-size: 1.1rem; white-space: pre-line; background: rgba(0, 0, 0, 0.2); padding: 1.5rem; border-radius: 8px; border: 1px solid rgba(255, 255, 255, 0.05);">{{ $cronograma }}</div>
            </div>
        @endif

        <!-- Manifesto Poético do Vaqueiro -->
        <div class="manifesto">
            <p class="poetry">
                "A adrenalina do boi na pista, a batida de esteira perfeita, o calor da arquibancada vibrando e a poeira dourada subindo sob o sol da tarde. Vaquejada não é apenas esporte ou competição... É o sangue correndo nas veias, a nossa cultura, o orgulho de ouvir o locutor gritar o consagrado: VALEU O BOI!"
            </p>

            <div class="highlight-box">
                <h2>Não Fique de Fora Dessa Festa!</h2>
                <p>O {{ \App\Models\Setting::getValue('parque.name', 'Parque de Vaquejada') }} convoca todos os vaqueiros, puxadores e bate-esteiras. Faça sua inscrição agora mesmo, realize o pagamento PIX simplificado e escolha sua 'pedra' (número da senha) direto pelo celular para garantir sua vaga na pista!</p>
                
                <div class="cta-group">
                    <a href="{{ route('portal.register') }}" class="btn btn-gold">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></svg>
                        Inscrever-se Agora
                    </a>
                    
                    <a href="{{ route('portal.login') }}" class="btn btn-outline">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path><polyline points="10 17 15 12 10 7"></polyline><line x1="15" y1="12" x2="3" y2="12"></svg>
                        Já Sou Cadastrado
                    </a>
                </div>
            </div>
        </div>

    </div>

    <footer class="site-footer">
        <div>
            &copy; {{ date('Y') }} {{ \App\Models\Setting::getValue('parque.name', 'Parque de Vaquejada') }} &bull; Arena Virtual
        </div>
        <div style="font-size: 0.85rem; color: rgba(255,255,255,0.4); font-weight: 500;">
            Desenvolvido por: <a href="https://instagram.com/emanuelxenos" target="_blank" style="color: var(--gold-light); text-decoration: none; font-weight: 700; border-bottom: 1px dashed var(--gold-light); transition: all 0.2s;">@emanuelxenos</a>
        </div>
        <!-- Link administrativo escondido sutilmente com baixíssimo contraste e sem chamar atenção -->
        <div>
            <a href="{{ route('login') }}" class="admin-link" title="Acesso Restrito">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                Painel
            </a>
        </div>
    </footer>

</body>
</html>
