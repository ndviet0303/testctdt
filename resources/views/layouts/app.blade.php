<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Hệ thống Quản lý Phê duyệt') - {{ config('app.name', 'Laravel') }}</title>
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --accent-color: #f093fb;
            --success-color: #4facfe;
            --warning-color: #f6d55c;
            --danger-color: #ff6b6b;
            --dark-color: #2d3748;
            --light-color: #f7fafc;
            --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            --border-radius: 16px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            color: var(--dark-color);
            padding-top: 80px; /* Space for fixed navbar */
        }

        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: none;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
        }

        .navbar-toggler {
            border: none;
            padding: 0.25rem 0.5rem;
        }

        .navbar-toggler:focus {
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%2833, 37, 41, 0.75%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary-color) !important;
        }

        .nav-link {
            font-weight: 500;
            color: var(--dark-color) !important;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: var(--primary-color) !important;
            transform: translateY(-1px);
        }

        .nav-link.active {
            color: var(--primary-color) !important;
            background: rgba(102, 126, 234, 0.1);
            border-radius: 8px;
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            border-radius: 12px;
            padding: 0.5rem 0;
            margin-top: 0.5rem;
        }

        .dropdown-item {
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
            border-radius: 0;
        }

        .dropdown-item:hover {
            background: rgba(102, 126, 234, 0.1);
            color: var(--primary-color);
        }

        .dropdown-item:active {
            background: rgba(102, 126, 234, 0.2);
            color: var(--primary-color);
        }

        .dropdown-header {
            padding: 0.75rem 1.5rem 0.5rem;
            font-size: 0.9rem;
        }

        .dropdown-divider {
            margin: 0.5rem 1rem;
        }

        .main-container {
            padding: 2rem 0;
            min-height: calc(100vh - 80px);
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
            border: none;
            padding: 1.5rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 25px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-success {
            background: linear-gradient(135deg, #4facfe, #00f2fe);
            border: none;
            border-radius: 25px;
            font-weight: 600;
        }

        .btn-danger {
            background: linear-gradient(135deg, #ff6b6b, #ee5a52);
            border: none;
            border-radius: 25px;
            font-weight: 600;
        }

        .btn-warning {
            background: linear-gradient(135deg, #f6d55c, #ed4264);
            border: none;
            border-radius: 25px;
            font-weight: 600;
            color: white;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .badge {
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 20px;
        }

        .badge.bg-warning {
            background: linear-gradient(135deg, #f6d55c, #feca57) !important;
            color: var(--dark-color);
        }

        .badge.bg-info {
            background: linear-gradient(135deg, #4facfe, #00f2fe) !important;
        }

        .badge.bg-success {
            background: linear-gradient(135deg, #4facfe, #00f2fe) !important;
        }

        .badge.bg-danger {
            background: linear-gradient(135deg, #ff6b6b, #ee5a52) !important;
        }

        .alert {
            border-radius: var(--border-radius);
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .table {
            background: white;
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--card-shadow);
        }

        .table th {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            font-weight: 600;
            padding: 1rem;
        }

        .table td {
            padding: 1rem;
            border-color: rgba(0, 0, 0, 0.05);
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background: rgba(102, 126, 234, 0.05);
        }

        .modal-content {
            border-radius: var(--border-radius);
            border: none;
            box-shadow: var(--card-shadow);
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
            border: none;
        }

        .progress {
            height: 8px;
            border-radius: 4px;
            background: rgba(102, 126, 234, 0.1);
        }

        .progress-bar {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 4px;
        }

        .timeline {
            position: relative;
            padding-left: 2rem;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 1rem;
            top: 0;
            bottom: 0;
            width: 2px;
            background: linear-gradient(to bottom, var(--primary-color), var(--accent-color));
        }

        .timeline-item {
            position: relative;
            margin-bottom: 2rem;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -1.5rem;
            top: 0.5rem;
            width: 1rem;
            height: 1rem;
            border-radius: 50%;
            background: white;
            border: 3px solid var(--primary-color);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .timeline-item.completed::before {
            background: var(--success-color);
            border-color: var(--success-color);
        }

        .timeline-item.rejected::before {
            background: var(--danger-color);
            border-color: var(--danger-color);
        }

        .stats-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 2rem;
            text-align: center;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            height: 100%;
        }

        .stats-card:hover {
            transform: translateY(-5px);
        }

        .stats-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
        }

        .stats-icon.pending { background: linear-gradient(135deg, var(--warning-color), #ed4264); }
        .stats-icon.approved { background: linear-gradient(135deg, var(--success-color), #00f2fe); }
        .stats-icon.rejected { background: linear-gradient(135deg, var(--danger-color), #ee5a52); }
        .stats-icon.workflows { background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); }

        .stats-number {
            font-size: 3rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }

        .stats-label {
            font-size: 1.1rem;
            color: #6c757d;
            font-weight: 500;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.8s ease-out;
        }

        @media (max-width: 768px) {
            body {
                padding-top: 70px; /* Smaller padding for mobile */
            }

            .main-container {
                padding: 1rem 0;
                min-height: calc(100vh - 70px);
            }
            
            .card {
                margin-bottom: 1rem;
            }

            .navbar {
                padding: 0.75rem 0; /* Smaller navbar on mobile */
            }

            .navbar-brand {
                font-size: 1.25rem;
            }

            .nav-link {
                padding: 0.75rem 1rem;
            }

            .dropdown-menu {
                border-radius: 8px;
                margin-top: 0.25rem;
            }

            .stats-card {
                margin-bottom: 1rem;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top" role="navigation" aria-label="Main navigation">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}" aria-label="Phenikaa University - Go to dashboard">
                <i class="fas fa-university me-2" aria-hidden="true"></i>
                Phenikaa University
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation menu">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto" role="menubar">
                    <li class="nav-item" role="none">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}" 
                           role="menuitem" aria-current="{{ request()->routeIs('dashboard') ? 'page' : 'false' }}">
                            <i class="fas fa-home me-1" aria-hidden="true"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item" role="none">
                        <a class="nav-link {{ request()->routeIs('organization.*') ? 'active' : '' }}" href="{{ route('organization.index') }}" 
                           role="menuitem" aria-current="{{ request()->routeIs('organization.*') ? 'page' : 'false' }}">
                            <i class="fas fa-sitemap me-1" aria-hidden="true"></i>
                            Cây tổ chức
                        </a>
                    </li>
                    @auth
                    @if(auth()->user()->hasAnyPermission(['view_requests', 'create_schools', 'propose_departments', 'approve_requests', 'create_requests']))
                    <li class="nav-item dropdown" role="none">
                        <a class="nav-link dropdown-toggle {{ request()->routeIs('approval.*') ? 'active' : '' }}" href="#" 
                           role="menuitem" data-bs-toggle="dropdown" aria-expanded="false" 
                           aria-haspopup="true" id="approvalDropdown">
                            <i class="fas fa-tasks me-1" aria-hidden="true"></i>
                            Quản lý Phê duyệt
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="approvalDropdown" role="menu">
                            @if(auth()->user()->hasPermission('view_requests'))
                            <li role="none"><a class="dropdown-item" href="{{ route('approval.index') }}" role="menuitem">
                                <i class="fas fa-tachometer-alt me-2" aria-hidden="true"></i>Dashboard
                            </a></li>
                            @endif
                            @if(auth()->user()->hasPermission('view_requests'))
                            <li role="none"><a class="dropdown-item" href="{{ route('approval.requests') }}" role="menuitem">
                                <i class="fas fa-list me-2" aria-hidden="true"></i>Tất cả yêu cầu
                            </a></li>
                            @endif
                            @if(auth()->user()->hasPermission('approve_requests'))
                            <li role="none"><a class="dropdown-item" href="{{ route('approval.my-tasks') }}" role="menuitem">
                                <i class="fas fa-user-check me-2" aria-hidden="true"></i>Công việc của tôi
                            </a></li>
                            @endif
                            @if(auth()->user()->hasPermission('create_requests'))
                            <li role="none"><a class="dropdown-item" href="{{ route('approval.create') }}" role="menuitem">
                                <i class="fas fa-plus-circle me-2" aria-hidden="true"></i>Tạo yêu cầu mới
                            </a></li>
                            @endif
                            @if(auth()->user()->hasPermission('create_schools') || auth()->user()->hasPermission('propose_departments'))
                            <li role="separator"><hr class="dropdown-divider"></li>
                            <li class="dropdown-header" role="none">
                                <small class="text-muted">Tạo đơn vị mới</small>
                            </li>
                            @endif
                            @if(auth()->user()->hasPermission('create_schools'))
                            <li role="none"><a class="dropdown-item" href="{{ route('approval.create-school') }}" role="menuitem">
                                <i class="fas fa-university me-2" aria-hidden="true"></i>Tạo Trường mới
                            </a></li>
                            @endif
                            @if(auth()->user()->hasPermission('propose_departments'))
                            <li role="none"><a class="dropdown-item" href="{{ route('approval.create-department') }}" role="menuitem">
                                <i class="fas fa-building me-2" aria-hidden="true"></i>Tạo Khoa mới
                            </a></li>
                            @endif
                        </ul>
                    </li>
                    @endif
                    @endauth
                </ul>
                
                <ul class="navbar-nav" role="menubar">
                    @auth
                    <li class="nav-item dropdown" role="none">
                        <a class="nav-link dropdown-toggle" href="#" role="menuitem" data-bs-toggle="dropdown" 
                           aria-expanded="false" aria-haspopup="true" id="userDropdown">
                            <i class="fas fa-user me-1" aria-hidden="true"></i>
                            {{ auth()->user()->name }}
                            <span class="badge bg-primary ms-2">{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown" role="menu">
                            <li class="dropdown-header" role="none">
                                <strong>{{ auth()->user()->name }}</strong><br>
                                <small class="text-muted">{{ auth()->user()->department }}</small>
                            </li>
                            <li role="separator"><hr class="dropdown-divider"></li>
                            <li role="none"><a class="dropdown-item" href="{{ route('login') }}" role="menuitem">
                                <i class="fas fa-exchange-alt me-2" aria-hidden="true"></i>Đổi tài khoản
                            </a></li>
                            <li role="none">
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item" role="menuitem">
                                        <i class="fas fa-sign-out-alt me-2" aria-hidden="true"></i>Đăng xuất
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    @else
                    <li class="nav-item" role="none">
                        <a class="nav-link" href="{{ route('login') }}" role="menuitem">
                            <i class="fas fa-sign-in-alt me-1" aria-hidden="true"></i>Đăng nhập
                        </a>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-container">
        <div class="container">
            <!-- Alerts -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Có lỗi xảy ra:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Page Content -->
            @yield('content')
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add CSRF token to all AJAX requests
        document.addEventListener('DOMContentLoaded', function() {
            // Set CSRF token for all AJAX requests
            const token = document.querySelector('meta[name="csrf-token"]');
            if (token) {
                window.axios = {
                    defaults: {
                        headers: {
                            'X-CSRF-TOKEN': token.getAttribute('content')
                        }
                    }
                };
            }

            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });

            // Add fade-in animation to cards
            const cards = document.querySelectorAll('.card, .stats-card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
                card.classList.add('fade-in-up');
            });
        });

        // Helper function for AJAX requests
        async function makeRequest(url, options = {}) {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            const defaultOptions = {
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                }
            };

            const mergedOptions = {
                ...defaultOptions,
                ...options,
                headers: {
                    ...defaultOptions.headers,
                    ...options.headers
                }
            };

            try {
                const response = await fetch(url, mergedOptions);
                const data = await response.json();
                
                if (!response.ok) {
                    throw new Error(data.message || 'Network response was not ok');
                }
                
                return data;
            } catch (error) {
                console.error('Request failed:', error);
                throw error;
            }
        }
    </script>
    
    @stack('scripts')
</body>
</html>
