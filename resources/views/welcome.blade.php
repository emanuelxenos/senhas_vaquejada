<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Controle - Inscrições & Senhas de Vaquejada</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-gradient: linear-gradient(135deg, #0b1329 0%, #030712 100%);
            --primary: #10b981;
            --primary-glow: rgba(16, 185, 129, 0.15);
            --amber: #f59e0b;
            --amber-glow: rgba(245, 158, 11, 0.15);
            --glass-bg: rgba(15, 23, 42, 0.45);
            --glass-border: rgba(255, 255, 255, 0.08);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-gradient);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow-x: hidden;
            position: relative;
        }

        /* Fundo com efeito de luz brilhante suave */
        body::before {
            content: "";
            position: absolute;
            top: -10%;
            left: 20%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.08) 0%, rgba(0,0,0,0) 70%);
            z-index: 0;
            pointer-events: none;
            filter: blur(50px);
        }
        
        body::after {
            content: "";
            position: absolute;
            bottom: -10%;
            right: 20%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(245, 158, 11, 0.05) 0%, rgba(0,0,0,0) 70%);
            z-index: 0;
            pointer-events: none;
            filter: blur(50px);
        }

        .container {
            width: 100%;
            max-width: 1100px;
            margin: 0 auto;
            padding: 2rem 1.5rem;
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

        .logo-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--glass-border);
            padding: 0.5rem 1.25rem;
            border-radius: 50px;
            margin-bottom: 1.5rem;
            font-weight: 600;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: #34d399;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 3.25rem;
            font-weight: 900;
            line-height: 1.15;
            letter-spacing: -1px;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #ffffff 30%, #a7f3d0 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .subtitle {
            font-size: 1.15rem;
            color: var(--text-muted);
            max-width: 650px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .portal-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .portal-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 3rem 2.5rem;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }

        .portal-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(800px circle at var(--x, 0px) var(--y, 0px), rgba(255,255,255,0.06), transparent 40%);
            z-index: 1;
            pointer-events: none;
        }

        .portal-card:hover {
            transform: translateY(-8px);
            border-color: rgba(255, 255, 255, 0.15);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.4);
        }

        .card-vaqueiro:hover {
            box-shadow: 0 30px 60px var(--primary-glow), 0 0 1px var(--primary);
        }

        .card-admin:hover {
            box-shadow: 0 30px 60px var(--amber-glow), 0 0 1px var(--amber);
        }

        .card-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 2rem;
            border: 1px solid rgba(255,255,255,0.05);
        }

        .icon-vaqueiro {
            background: rgba(16, 185, 129, 0.1);
            color: var(--primary);
            border-color: rgba(16, 185, 129, 0.2);
        }

        .icon-admin {
            background: rgba(245, 158, 11, 0.1);
            color: var(--amber);
            border-color: rgba(245, 158, 11, 0.2);
        }

        .card-content h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            color: #fff;
        }

        .card-content p {
            color: var(--text-muted);
            font-size: 1rem;
            line-height: 1.5;
            margin-bottom: 2.5rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.25s ease;
            cursor: pointer;
            gap: 0.5rem;
            z-index: 5;
            position: relative;
        }

        .btn-primary {
            background: var(--primary);
            color: #030712;
        }

        .btn-primary:hover {
            background: #34d399;
            transform: scale(1.02);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
        }

        .btn-amber {
            background: var(--amber);
            color: #030712;
        }

        .btn-amber:hover {
            background: #fbbf24;
            transform: scale(1.02);
            box-shadow: 0 8px 25px rgba(245, 158, 11, 0.3);
        }

        .card-footer {
            margin-top: 1.25rem;
            text-align: center;
            font-size: 0.9rem;
            color: var(--text-muted);
        }

        .card-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .card-footer a:hover {
            text-decoration: underline;
        }

        footer.site-footer {
            text-align: center;
            padding: 1.5rem;
            font-size: 0.85rem;
            color: rgba(255,255,255,0.25);
            border-top: 1px solid rgba(255,255,255,0.02);
            z-index: 10;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            h1 {
                font-size: 2.25rem;
            }
            
            .subtitle {
                font-size: 1rem;
            }

            .portal-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .portal-card {
                padding: 2.25rem 1.75rem;
            }

            .card-content p {
                margin-bottom: 1.75rem;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        
        <header>
            <div class="logo-badge">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                Parque de Vaquejada
            </div>
            <h1>Inscrições & Escolha de Senhas</h1>
            <p class="subtitle">Bem-vindo ao sistema oficial do parque. Escolha sua opção abaixo para entrar no painel de controle correspondente.</p>
        </header>

        <div class="portal-grid">
            
            <!-- Card do Vaqueiro -->
            <div class="portal-card card-vaqueiro" id="card-vaqueiro">
                <div class="card-content">
                    <div class="card-icon icon-vaqueiro">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    </div>
                    <h2>Portal do Vaqueiro</h2>
                    <p>Espaço exclusivo do competidor. Faça login para cadastrar suas inscrições, pagar o PIX imediato, escolher as suas senhas de corrida e baixar seus comprovantes.</p>
                </div>
                <div>
                    <a href="{{ route('portal.login') }}" class="btn btn-primary">
                        Entrar no Portal
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                    </a>
                    <div class="card-footer">
                        Não tem cadastro? <a href="{{ route('portal.register') }}">Crie sua conta</a>
                    </div>
                </div>
            </div>

            <!-- Card da Secretaria -->
            <div class="portal-card card-admin" id="card-admin">
                <div class="card-content">
                    <div class="card-icon icon-admin">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    </div>
                    <h2>Secretaria & Caixa</h2>
                    <p>Área restrita de gestão do evento. Acesse para realizar inscrições de pista em dinheiro, validar pagamentos manuais, comandar o locutor e emitir relatórios financeiros.</p>
                </div>
                <div>
                    <a href="{{ route('login') }}" class="btn btn-amber">
                        Acesso Administrativo
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                    </a>
                    <div class="card-footer">
                        Restrito a organizadores, caixas e locutores.
                    </div>
                </div>
            </div>

        </div>

    </div>

    <footer class="site-footer">
        &copy; {{ date('Y') }} {{ \App\Models\Setting::getValue('parque.name', 'Parque de Vaquejada') }}. Todos os direitos reservados.
    </footer>

    <!-- Script para efeito hover 3D moderno (Glow Mouse Follow) -->
    <script>
        const cards = document.querySelectorAll('.portal-card');
        
        cards.forEach(card => {
            card.addEventListener('mousemove', e => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                card.style.setProperty('--x', `${x}px`);
                card.style.setProperty('--y', `${y}px`);
            });
        });
    </script>
</body>
</html>
