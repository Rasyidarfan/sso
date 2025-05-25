<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SSO BPS')</title>
    <link rel="shortcut icon" href="{{ asset('icon.ico') }}" type="image/x-icon">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f5f5f5;
            overflow-x: hidden;
        }
        
        .navbar {
            background: #0099dd;
            padding: 1rem;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 100;
            color: white;
        }
        
        .navbar-brand {
            display: flex;
            align-items: center;
        }
        
        .navbar-brand img {
            height: 30px;
            margin-right: 10px;
        }
        
        .main-content-wrapper {
            display: flex;
            min-height: 100vh;
            padding-top: 70px; /* Navbar height */
        }
        
        .sidebar {
            width: 250px;
            background: white;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            padding-top: 20px;
            position: fixed;
            height: calc(100vh - 70px);
            transition: all 0.3s;
            overflow-y: auto;
        }
        
        .content-area {
            flex: 1;
            transition: all 0.3s;
            padding: 20px;
            margin-left: 250px; /* Same as sidebar width */
        }
        
        /* Mobile sidebar mechanics */
        @media (max-width: 991.98px) {
            .sidebar {
                left: -250px;
                z-index: 99;
                padding-top: 20px;
            }
            
            .sidebar.active {
                left: 0;
            }
            
            .content-area {
                margin-left: 0;
            }
            
            .main-content-wrapper.active .content-area {
                margin-left: 250px;
            }
            
            .overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.5);
                display: none;
                z-index: 98;
            }
            
            .overlay.active {
                display: block;
            }
        }
        
        .burger-menu {
            cursor: pointer;
            display: none;
        }
        
        @media (max-width: 991.98px) {
            .burger-menu {
                display: block;
            }
        }
        
        .sidebar-link {
            display: block;
            padding: 15px 25px;
            color: #333;
            text-decoration: none;
            border-bottom: 1px solid #eee;
        }
        
        .sidebar-link:hover {
            background: #f5f5f5;
        }
        
        .sidebar-title {
            font-size: 18px;
            font-weight: bold;
            padding: 15px 25px 5px;
            color: #0099dd;
        }
        
        .card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .btn-primary {
            background: #0099dd;
            border-color: #0099dd;
        }
        
        .btn-primary:hover {
            background: #0088cc;
            border-color: #0088cc;
        }
        
        .role-tag {
            background: #e3f2fd;
            color: #0099dd;
            padding: 5px 15px;
            border-radius: 15px;
            font-size: 14px;
            display: inline-block;
            margin: 2px;
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center w-100">
                <div class="burger-menu">
                    <i class="fas fa-bars"></i>
                </div>
                <div class="navbar-brand">
                    <img src="{{ asset('Logo_tagline.png') }}" alt="BPS Logo">
                    <span>SSO BPS</span>
                </div>
                <div></div>
            </div>
        </div>
    </nav>

    <div class="main-content-wrapper">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-title">Menu</div>
            @auth
                <a href="{{ route('home') }}" class="sidebar-link">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a href="{{ route('profile') }}" class="sidebar-link">
                    <i class="fas fa-user"></i> Profile
                </a>
                @if(Auth::user()->canManageUsers())
                <a href="{{ route('users.index') }}" class="sidebar-link">
                    <i class="fas fa-users"></i> Daftar Akun
                </a>
                @endif
                @if(Auth::user()->isAdmin())
                <a href="{{ route('client-apps.index') }}" class="sidebar-link">
                    <i class="fas fa-code"></i> API & Aplikasi
                </a>
                @endif
                <a href="{{ route('api.docs') }}" class="sidebar-link">
                    <i class="fas fa-book"></i> Dokumentasi API
                </a>
                <a href="{{ route('logout') }}" class="sidebar-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @else
                <a href="{{ route('login') }}" class="sidebar-link">
                    <i class="fas fa-sign-in-alt"></i> Login
                </a>
            @endauth
        </div>

        <!-- Overlay for mobile -->
        <div class="overlay"></div>

        <!-- Main Content -->
        <div class="content-area">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('message'))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    {{ session('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Burger menu functionality - only active on mobile
        const burgerMenu = document.querySelector('.burger-menu');
        const sidebar = document.querySelector('.sidebar');
        const overlay = document.querySelector('.overlay');
        const mainContentWrapper = document.querySelector('.main-content-wrapper');

        burgerMenu.addEventListener('click', () => {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
            mainContentWrapper.classList.toggle('active');
        });

        overlay.addEventListener('click', () => {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            mainContentWrapper.classList.remove('active');
        });

        // Hide overlay on window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth > 991.98) {
                overlay.classList.remove('active');
                sidebar.classList.remove('active');
                mainContentWrapper.classList.remove('active');
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
