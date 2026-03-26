<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senhas de Vaquejada</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-collapsed: 80px;
            --primary-color: #0d6efd;
            --sidebar-bg: #2c3e50;
            --sidebar-hover: #34495e;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            display: flex;
            background-color: #f5f6fa;
            min-height: 100vh;
        }

        /* SIDEBAR */
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--sidebar-bg);
            color: white;
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            overflow-y: auto;
            overflow-x: hidden;
            transition: width 0.3s ease;
            z-index: 1000;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed);
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sidebar-logo {
            font-size: 24px;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: opacity 0.3s ease;
        }

        .sidebar.collapsed .sidebar-logo span {
            display: none;
        }

        .sidebar-toggle {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            font-size: 18px;
            transition: color 0.3s ease;
        }

        .sidebar-toggle:hover {
            color: var(--primary-color);
        }

        /* MENU */
        .sidebar-menu {
            list-style: none;
            padding: 15px 0;
        }

        .menu-section {
            margin-bottom: 10px;
        }

        .menu-section-title {
            padding: 10px 20px 5px 20px;
            font-size: 12px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.5);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: opacity 0.3s ease;
        }

        .sidebar.collapsed .menu-section-title {
            opacity: 0;
            height: 0;
            padding: 0;
            margin: 0;
            overflow: hidden;
        }

        .menu-item {
            position: relative;
        }

        .menu-link {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px 20px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .menu-link:hover {
            background-color: var(--sidebar-hover);
            color: white;
            border-left-color: var(--primary-color);
            padding-left: 17px;
        }

        .menu-link.active {
            background-color: var(--primary-color);
            color: white;
            border-left-color: #0a58ca;
            font-weight: 600;
        }

        .menu-icon {
            min-width: 20px;
            text-align: center;
            font-size: 18px;
        }

        .menu-label {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            transition: opacity 0.3s ease;
        }

        .sidebar.collapsed .menu-label {
            opacity: 0;
            width: 0;
            display: none;
        }

        /* TOP BAR */
        .topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: 70px;
            background: white;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
            transition: left 0.3s ease;
            z-index: 999;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .sidebar.collapsed ~ .topbar {
            left: var(--sidebar-collapsed);
        }

        .topbar-title {
            font-size: 24px;
            font-weight: 600;
            color: #2c3e50;
        }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-info {
            text-align: right;
        }

        .user-name {
            font-weight: 600;
            color: #2c3e50;
            font-size: 14px;
        }

        .user-role {
            font-size: 12px;
            color: #7f8c8d;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 18px;
        }

        /* MAIN CONTENT */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: 70px;
            padding: 30px;
            flex: 1;
            transition: margin-left 0.3s ease;
        }

        .sidebar.collapsed ~ .topbar, 
        .sidebar.collapsed ~ .main-content {
            margin-left: var(--sidebar-collapsed);
        }

        .content-wrapper {
            background: white;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        /* ALERTS */
        .alert-container {
            margin-bottom: 20px;
        }

        /* MOBILE MENU */
        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--sidebar-bg);
            font-size: 24px;
            cursor: pointer;
        }

        @media (max-width: 992px) {
            :root {
                --sidebar-width: 260px;
            }

            .sidebar {
                margin-left: -260px;
            }

            .sidebar.show {
                margin-left: 0;
            }

            .topbar {
                left: 0;
            }

            .main-content {
                margin-left: 0;
            }

            .mobile-toggle {
                display: block;
            }

            .sidebar-toggle {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .topbar {
                padding: 0 15px;
            }

            .main-content {
                padding: 15px;
            }

            .content-wrapper {
                padding: 15px;
            }

            .topbar-title {
                font-size: 18px;
            }

            .user-info {
                display: none;
            }
        }

        /* LOGOUT BUTTON */
        .logout-section {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: auto;
            padding: 15px 0;
        }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px 20px;
            color: #e74c3c;
            text-decoration: none;
            border: none;
            background: none;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: left;
            font-size: 14px;
        }

        .logout-btn:hover {
            background-color: rgba(231, 76, 60, 0.1);
            padding-left: 17px;
        }
    </style>
</head>
<body>
@auth
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <i class="fas fa-horse-head"></i>
                <span>Vaquejada</span>
            </div>
            <button class="sidebar-toggle" onclick="toggleSidebar()" title="Recolher">
                <i class="fas fa-chevron-left"></i>
            </button>
        </div>

        <nav class="sidebar-menu" style="display: flex; flex-direction: column; height: calc(100vh - 130px);">
            <!-- GERENCIAMENTO -->
            <div class="menu-section">
                <div class="menu-section-title">Gerenciamento</div>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <li class="menu-item">
                        <a href="{{ route('dashboard') }}" class="menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="fas fa-chart-line menu-icon"></i>
                            <span class="menu-label">Dashboard</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('competidores.index') }}" class="menu-link {{ request()->routeIs('competidores*') ? 'active' : '' }}">
                            <i class="fas fa-users menu-icon"></i>
                            <span class="menu-label">Competidores</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('inscricoes.index') }}" class="menu-link {{ request()->routeIs('inscricoes*') ? 'active' : '' }}">
                            <i class="fas fa-clipboard-list menu-icon"></i>
                            <span class="menu-label">Inscrições</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('senhas.index') }}" class="menu-link {{ request()->routeIs('senhas*') ? 'active' : '' }}">
                            <i class="fas fa-hashtag menu-icon"></i>
                            <span class="menu-label">Senhas</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- AÇÕES -->
            <div class="menu-section">
                <div class="menu-section-title">Ações</div>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <li class="menu-item">
                        <a href="{{ route('senhas.create') }}" class="menu-link">
                            <i class="fas fa-plus-circle menu-icon"></i>
                            <span class="menu-label">Cadastrar Senhas</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('relatorio') }}" class="menu-link">
                            <i class="fas fa-file-pdf menu-icon"></i>
                            <span class="menu-label">Relatório</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- CONFIGURAÇÕES -->
            <div class="menu-section">
                <div class="menu-section-title">Configuração</div>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <li class="menu-item">
                        <a href="{{ route('settings.index') }}" class="menu-link {{ request()->routeIs('settings*') ? 'active' : '' }}">
                            <i class="fas fa-cog menu-icon"></i>
                            <span class="menu-label">Configurações</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- PARA O FUTURO -->
            <div class="menu-section" style="opacity: 0.5;">
                <div class="menu-section-title">Em Breve</div>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <li class="menu-item">
                        <a href="#" class="menu-link" onclick="return false;">
                            <i class="fas fa-credit-card menu-icon"></i>
                            <span class="menu-label">Pagamentos</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="menu-link" onclick="return false;">
                            <i class="fas fa-chart-bar menu-icon"></i>
                            <span class="menu-label">Análises</span>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="menu-link" onclick="return false;">
                            <i class="fas fa-cog menu-icon"></i>
                            <span class="menu-label">Configurações</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- LOGOUT - Fixed at bottom -->
            <div class="logout-section" style="margin-top: auto;">
                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt menu-icon"></i>
                        <span class="menu-label">Sair</span>
                    </button>
                </form>
            </div>
        </nav>
    </aside>

    <!-- TOPBAR -->
    <header class="topbar">
        <div style="display: flex; align-items: center; gap: 20px;">
            <button class="mobile-toggle" onclick="toggleSidebarMobile()" title="Menu">
                <i class="fas fa-bars"></i>
            </button>
            <h1 class="topbar-title">@yield('page-title', 'Dashboard')</h1>
        </div>
        <div class="topbar-user">
            <div class="user-info">
                <div class="user-name">{{ Auth::user()->name }}</div>
                <div class="user-role">Administrador</div>
            </div>
            <div class="user-avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="main-content">
        @if(session('success'))
            <div class="alert-container">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="alert-container">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        <div class="content-wrapper">
            @yield('content')
        </div>
    </main>
@else
    <!-- Para usuários não autenticados, mostra apenas o conteúdo -->
    <div style="width: 100%; padding: 0;">
        @yield('content')
    </div>
@endauth

<script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

<script>
    // Recuperar estado do sidebar do localStorage
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarState = localStorage.getItem('sidebarCollapsed');
        if (sidebarState === 'true') {
            document.querySelector('.sidebar').classList.add('collapsed');
        }
    });

    // Toggle Sidebar (Desktop)
    function toggleSidebar() {
        const sidebar = document.querySelector('.sidebar');
        sidebar.classList.toggle('collapsed');
        
        // Salvar estado no localStorage
        const isCollapsed = sidebar.classList.contains('collapsed');
        localStorage.setItem('sidebarCollapsed', isCollapsed);
    }

    // Toggle Sidebar (Mobile)
    function toggleSidebarMobile() {
        const sidebar = document.querySelector('.sidebar');
        sidebar.classList.toggle('show');
    }

    // Fechar sidebar ao clicar em um link (mobile)
    document.querySelectorAll('.menu-link').forEach(link => {
        link.addEventListener('click', function() {
            const sidebar = document.querySelector('.sidebar');
            if (window.innerWidth <= 992) {
                sidebar.classList.remove('show');
            }
        });
    });
</script>

@yield('scripts')
</body>
</html>
