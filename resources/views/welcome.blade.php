<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ \App\Models\Setting::getValue('parque.name', 'Parque de Vaquejada') }} - Arena Oficial</title>
    
    <!-- Google Fonts: Playfair Display para títulos rústicos/fortes e Inter para leitura -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@800;900&family=Cinzel+Decorative:wght@700;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            /* Paleta rústica premium: tons de couro, ouro velho, pôr-do-sol da arena e poeira dourada */
            --bg-arena: linear-gradient(185deg, #1f0f05 0%, #0c0501 100%);
            --leather-dark: #1f1107;
            --gold-old: #d97706;
            --gold-glow: rgba(217, 119, 6, 0.2);
            --sunset-orange: #ea580c;
            --sunset-glow: rgba(234, 88, 12, 0.2);
            --sand-dust: #fef3c7;
            --glass-bg: rgba(31, 17, 7, 0.55);
            --glass-border: rgba(217, 119, 6, 0.15);
            --text-gold: #fde047;
            --text-light: #fffbeb;
            --text-sand: #d97706;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-arena);
            color: var(--text-light);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow-x: hidden;
            position: relative;
        }

        /* Efeito de Sol da Tarde / Poeira de Arena no fundo */
        body::before {
            content: "";
            position: absolute;
            top: -10%;
            left: 50%;
            transform: translateX(-50%);
            width: 800px;
            height: 400px;
            background: radial-gradient(circle, rgba(234, 88, 12, 0.12) 0%, rgba(0,0,0,0) 70%);
            z-index: 0;
            pointer-events: none;
            filter: blur(80px);
        }

        /* Textura rústica sutil no fundo (linhas de poeira) */
        .dust-overlay {
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(217, 119, 6, 0.03) 1px, transparent 0);
            background-size: 24px 24px;
            z-index: 1;
            pointer-events: none;
        }

        .container {
            width: 100%;
            max-width: 1000px;
            margin: 0 auto;
            padding: 3rem 1.5rem;
            z-index: 10;
            position: relative;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        header {
            text-align: center;
            margin-bottom: 4rem;
        }

        /* Emblema rústico de ferradura / estrela */
        .event-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            background: rgba(217, 119, 6, 0.08);
            border: 1px solid rgba(217, 119, 6, 0.3);
            color: var(--text-gold);
            padding: 0.6rem 1.5rem;
            border-radius: 4px;
            margin-bottom: 1.5rem;
            font-weight: 700;
            font-size: 0.8rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            box-shadow: 0 4px 20px rgba(0,0,0,0.4);
            border-top: 2px solid var(--gold-old);
        }

        h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 3.75rem;
            font-weight: 900;
            line-height: 1.1;
            letter-spacing: -0.5px;
            margin-bottom: 1rem;
            text-transform: uppercase;
            text-shadow: 0 4px 15px rgba(0,0,0,0.6);
            background: linear-gradient(135deg, #ffffff 40%, #ffedd5 70%, #d97706 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .subtitle {
            font-size: 1.2rem;
            color: #fed7aa;
            max-width: 700px;
            margin: 0 auto;
            line-height: 1.6;
            font-weight: 500;
            text-shadow: 0 2px 4px rgba(0,0,0,0.4);
        }

        .portal-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2.5rem;
            margin-bottom: 2rem;
        }

        /* Card estilo Ingresso de Bilheteria / Couro rústico */
        .portal-card {
            background: var(--glass-bg);
            border: 2px solid var(--glass-border);
            border-radius: 16px;
            padding: 3rem 2.5rem;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
        }

        /* Detalhe de perfuração de ingresso nas laterais para reforçar o tema evento/bilhete */
        .portal-card::before, .portal-card::after {
            content: "";
            position: absolute;
            top: 50%;
            width: 20px;
            height: 20px;
            background: #0c0501;
            border-radius: 50%;
            z-index: 5;
        }

        .portal-card::before {
            left: -11px;
            border-right: 2px solid var(--glass-border);
        }

        .portal-card::after {
            right: -11px;
            border-left: 2px solid var(--glass-border);
        }

        .portal-card:hover {
            transform: translateY(-6px);
            background: rgba(45, 25, 10, 0.65);
        }

        .card-vaqueiro {
            border-top: 4px solid var(--gold-old);
        }

        .card-vaqueiro:hover {
            box-shadow: 0 30px 60px var(--gold-glow), 0 0 1px var(--gold-old);
            border-color: rgba(217, 119, 6, 0.4);
        }

        .card-admin {
            border-top: 4px solid var(--sunset-orange);
        }

        .card-admin:hover {
            box-shadow: 0 30px 60px var(--sunset-glow), 0 0 1px var(--sunset-orange);
            border-color: rgba(234, 88, 12, 0.4);
        }

        .card-icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 2rem;
            background: rgba(217, 119, 6, 0.08);
            border: 2px solid rgba(217, 119, 6, 0.2);
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }

        .icon-vaqueiro {
            color: var(--text-gold);
            border-color: rgba(217, 119, 6, 0.3);
        }

        .icon-admin {
            color: #fdba74;
            border-color: rgba(253, 186, 116, 0.3);
            background: rgba(234, 88, 12, 0.08);
        }

        .card-content h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.85rem;
            font-weight: 800;
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #fff;
            text-shadow: 0 2px 4px rgba(0,0,0,0.5);
        }

        .card-content p {
            color: #fed7aa;
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 2.5rem;
            opacity: 0.85;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 1.15rem 1.5rem;
            border-radius: 8px;
            font-size: 1.05rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-decoration: none;
            transition: all 0.25s ease;
            cursor: pointer;
            gap: 0.6rem;
            z-index: 5;
            position: relative;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
            border-bottom: 3px solid rgba(0,0,0,0.3);
        }

        .btn-gold {
            background: linear-gradient(to bottom, #f59e0b, #d97706);
            color: #1f0f05;
        }

        .btn-gold:hover {
            background: linear-gradient(to bottom, #fbbf24, #f59e0b);
            transform: scale(1.01);
            box-shadow: 0 8px 25px rgba(217, 119, 6, 0.4);
        }

        .btn-sunset {
            background: linear-gradient(to bottom, #ea580c, #c2410c);
            color: #fff;
        }

        .btn-sunset:hover {
            background: linear-gradient(to bottom, #f97316, #ea580c);
            transform: scale(1.01);
            box-shadow: 0 8px 25px rgba(234, 88, 12, 0.4);
        }

        .card-footer {
            margin-top: 1.5rem;
            text-align: center;
            font-size: 0.9rem;
            color: #ffedd5;
            opacity: 0.8;
            font-weight: 500;
        }

        .card-footer a {
            color: var(--text-gold);
            text-decoration: none;
            font-weight: 700;
            border-bottom: 1px dashed var(--text-gold);
            padding-bottom: 2px;
        }

        .card-footer a:hover {
            color: #fff;
            border-color: #fff;
        }

        footer.site-footer {
            text-align: center;
            padding: 2rem;
            font-size: 0.9rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: rgba(253, 224, 71, 0.4);
            border-top: 1px solid rgba(217, 119, 6, 0.08);
            background: rgba(12, 5, 1, 0.8);
            z-index: 10;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            h1 {
                font-size: 2.5rem;
            }
            
            .subtitle {
                font-size: 1.05rem;
            }

            .portal-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .portal-card {
                padding: 2.5rem 1.75rem;
            }

            .card-content p {
                margin-bottom: 2rem;
            }
        }
    </style>
</head>
<body>

    <div class="dust-overlay"></div>

    <div class="container">
        
        <header>
            <div class="event-badge">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                Arena de Eventos
            </div>
            <!-- Pega o Nome do Parque de forma 100% dinâmica -->
            <h1>{{ \App\Models\Setting::getValue('parque.name', 'Parque de Vaquejada') }}</h1>
            <p class="subtitle">Escolha seu acesso abaixo para entrar na arena virtual. Corra contra o tempo, garanta sua senha e acompanhe sua dupla!</p>
        </header>

        <div class="portal-grid">
            
            <!-- Card do Vaqueiro -->
            <div class="portal-card card-vaqueiro">
                <div class="card-content">
                    <div class="card-icon icon-vaqueiro">
                        <!-- Ícone de Competidor / Chapéu ou Usuário -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    </div>
                    <h2>Área do Competidor</h2>
                    <p>Espaço exclusivo para vaqueiros. Faça login para cadastrar suas duplas de corrida, realizar pagamento via PIX imediato, escolher e garantir seus números de senhas.</p>
                </div>
                <div>
                    <a href="{{ route('portal.login') }}" class="btn btn-gold">
                        Entrar no Portal
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                    </a>
                    <div class="card-footer">
                        Ainda não se cadastrou? <a href="{{ route('portal.register') }}">Inscreva-se Aqui</a>
                    </div>
                </div>
            </div>

            <!-- Card da Secretaria -->
            <div class="portal-card card-admin">
                <div class="card-content">
                    <div class="card-icon icon-admin">
                        <!-- Ícone de Cadeado / Controle -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    </div>
                    <h2>Secretaria & Caixa</h2>
                    <p>Acesso exclusivo para os organizadores do evento. Gerencie inscrições de pista, controle o caixa manual em dinheiro viva, coordene o locutor e emita relatórios.</p>
                </div>
                <div>
                    <a href="{{ route('login') }}" class="btn btn-sunset">
                        Entrar na Secretaria
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                    </a>
                    <div class="card-footer">
                        Acesso restrito à comissão organizadora.
                    </div>
                </div>
            </div>

        </div>

    </div>

    <footer class="site-footer">
        &copy; {{ date('Y') }} {{ \App\Models\Setting::getValue('parque.name', 'Parque de Vaquejada') }} &bull; Painel de Controle Oficial
    </footer>

</body>
</html>
