<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'MotoGo') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts - Arapça için uygun font -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        :root {
            --dark-gray: rgba(26, 26, 26);
            --gray: rgba(52, 52, 52);
            --red-primary: rgba(207, 29, 25);
            --red-secondary: rgba(238, 84, 87);
            --white: rgba(255, 255, 255);
        }

        body {
            font-family: 'Tajawal', sans-serif;
            color: var(--dark-gray);
            overflow-x: hidden;
            direction: rtl;
            text-align: right;
            transition: margin-right 0.3s ease;
        }

        .bg-dark-custom {
            background-color: var(--dark-gray) !important;
        }

        .bg-gray-custom {
            background-color: var(--gray);
        }

        .text-red-primary {
            color: var(--red-primary);
        }

        .text-red-secondary {
            color: var(--red-secondary);
        }

        .bg-red-primary {
            background-color: var(--red-primary);
        }

        .bg-red-secondary {
            background-color: var(--red-secondary);
        }

        .btn-red-primary {
            background-color: var(--red-primary);
            color: white;
            border: none;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .btn-red-primary:hover {
            background-color: var(--red-secondary);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(207, 29, 25, 0.3);
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            right: -300px;
            width: 300px;
            height: 100vh;
            background: var(--dark-gray);
            box-shadow: -5px 0 15px rgba(0, 0, 0, 0.3);
            transition: right 0.3s ease;
            z-index: 1050;
            overflow-y: auto;
            padding: 1rem;
        }

        .sidebar.open {
            right: 0;
        }

        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1049;
            display: none;
        }

        .sidebar-overlay.show {
            display: block;
        }

        .sidebar-brand {
            font-weight: 700;
            font-size: 1.6rem;
            display: flex;
            align-items: center;
            color: white !important;
            padding: 1rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 1rem;
        }

        .sidebar-brand span {
            color: var(--red-primary);
        }

        .sidebar-nav {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-nav .nav-item {
            margin-bottom: 0.3rem;
        }

        .sidebar-nav .nav-link {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            padding: 0.8rem 1rem;
            border-radius: 6px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .sidebar-nav .nav-link:hover,
        .sidebar-nav .nav-link.active {
            color: white;
            background: rgba(207, 29, 25, 0.15);
        }

        .sidebar-nav .nav-link i {
            margin-left: 0.5rem;
            width: 20px;
            text-align: center;
        }

        .sidebar-divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.1);
            margin: 1rem 0;
        }

        /* Top Header Styles */
        .top-header {
            position: fixed;
            top: 0;
            right: 0;
            left: 0;
            height: 70px;
            background: var(--dark-gray);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            z-index: 1030;
            display: flex;
            align-items: center;
            padding: 0 1rem;
        }

        .menu-toggle {
            background: none;
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            border-radius: 6px;
            padding: 0.5rem 0.7rem;
            margin-left: 1rem;
            transition: all 0.3s ease;
        }

        .menu-toggle:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .header-brand {
            font-weight: 700;
            font-size: 1.4rem;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .header-brand span {
            color: var(--red-primary);
        }

        .header-actions {
            margin-right: auto;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Dropdown Styles */
        .dropdown-menu {
            background: var(--gray);
            border: none;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            padding: 0.5rem;
        }

        .dropdown-item {
            color: rgba(255, 255, 255, 0.9);
            padding: 0.5rem 1rem;
            border-radius: 6px;
            transition: all 0.3s ease;
            font-weight: 500;
            font-size: 0.9rem;
        }

        footer {
            background-color: var(--dark-gray);
            color: white;
            padding:20px 0 30px;
            height: 55vh;

            display: flex;
            justify-content: center;
            align-items: center;

        }
            footer * {

                transform: scale(1.03);
            }

        .dropdown-item:hover {
            background: var(--red-primary);
            color: white;
        }

        /* Responsive Adjustments */
        @media (max-width: 767.98px) {
            .sidebar {
                width: 280px;
                right: -280px;
            }

            footer {

                display: flex;
                justify-content: center;
                align-items: center;

                height: 100vh;



            }



            .header-brand {
                font-size: 1.2rem;
            }

            .btn-red-primary {
                font-size: 0.8rem;
                padding: 0.4rem 0.8rem;
            }
        }

        @media (max-width: 575.98px) {
            .sidebar {
                width: 260px;
                right: -260px;
            }

            .header-brand {
                font-size: 1.1rem;
            }
        }

        /* RTL Adjustments */
        .me-2 {
            margin-left: 0.5rem !important;
            margin-right: 0 !important;
        }

        .ms-auto {
            margin-right: auto !important;
            margin-left: 0 !important;
        }

        .text-start {
            text-align: right !important;
        }

        .text-end {
            text-align: left !important;
        }




        .footer-links a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .footer-links a:hover {
            color: var(--red-secondary);
        }

        .social-icons a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: white;
            margin-left: 10px;
            transition: all 0.3s ease;
        }

        .social-icons a:hover {
            background-color: var(--red-primary);
            transform: translateY(-5px);
        }
    </style>
</head>

<body>
    <div id="app">
        <!-- Sidebar Overlay -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Sidebar -->
        <div class="sidebar" id="sidebar">
            <!-- Brand -->
            <a class="sidebar-brand" href="{{ url('/') }}">
                <i class="fas fa-shipping-fast me-2"></i>موتو<span>جو</span>
            </a>

            <!-- Navigation Links -->
            <ul class="sidebar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">
                        <i class="fas fa-home"></i>الرئيسية
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#about">
                        <i class="fas fa-info-circle"></i>من نحن
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#services">
                        <i class="fas fa-concierge-bell"></i>خدماتنا
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#contact">
                        <i class="fas fa-phone"></i>اتصل بنا
                    </a>
                </li>
                @role('admin')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer"></i>لوحة التحكم
                        </a>
                    </li>
                @endrole

                <div class="sidebar-divider"></div>

                <!-- Restaurant Order Button -->
                <li class="nav-item">
                    <a href="{{ route('restaurants.index') }}" class="nav-link btn-red-primary text-center">
                        <i class="fas fa-utensils me-1"></i>
                        اطلب من مطعمك المفضل
                    </a>
                </li>

                <div class="sidebar-divider"></div>

                <!-- Authentication Links -->
                @guest
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt"></i>تسجيل الدخول
                            </a>
                        </li>
                    @endif

                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="fas fa-user-plus"></i>إنشاء حساب
                            </a>
                        </li>
                    @endif
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-user-circle"></i>{{ Auth::user()->name }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('orders.index') }}">
                            <i class="fas fa-shopping-bag"></i>طلباتي
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i>تسجيل الخروج
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                @endguest
            </ul>
        </div>

        <!-- Top Header -->
        <div class="top-header">
            <button class="menu-toggle" id="menuToggle">
                <i class="fas fa-bars"></i>
            </button>

            <a class="header-brand" href="{{ url('/') }}">
                <i class="fas fa-shipping-fast me-2"></i>موتو<span>جو</span>
            </a>

            <div class="header-actions">
                @auth
                    <div class="dropdown">
                        <button class="btn btn-red-primary dropdown-toggle" type="button" id="userDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user me-1"></i>{{ Auth::user()->name }}
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="userDropdown">
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user-circle me-2"></i>الملف الشخصي
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('orders.index') }}">
                                    <i class="fas fa-shopping-bag me-2"></i>طلباتي
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form-header').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i>تسجيل الخروج
                                </a>
                                <form id="logout-form-header" action="{{ route('logout') }}" method="POST"
                                    class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    @if (Route::has('login'))
                        <a class="btn btn-outline-light me-1" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt me-1"></i>تسجيل الدخول
                        </a>
                    @endif
                @endauth

                <a href="{{ route('restaurants.index') }}" class="btn btn-red-primary">
                    <i class="fas fa-utensils me-1"></i>
                    <span class="d-none d-sm-inline">طلب الطعام</span>
                </a>
            </div>
        </div>

        <main class="py-4" style="margin-top: 80px;">
            @yield('content')
        </main>
    </div>
    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h4 class="mb-4">
                        <i class="fas fa-shipping-fast me-2"></i>موتو<span>جو</span>
                    </h4>
                    <p class="mb-4">شركة رائدة في خدمات الشحن والتوصيل في حماة، سوريا. نقدم حلولاً لوجستية متكاملة مع
                        التركيز على جودة الخدمة وسرعة التوصيل.</p>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                    <h5 class="mb-4">روابط سريعة</h5>
                    <div class="footer-links">
                        <p><a href="#home">الرئيسية</a></p>
                        <p><a href="#about">من نحن</a></p>
                        <p><a href="#services">خدماتنا</a></p>
                        <p><a href="#contact">اتصل بنا</a></p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 mb-4 mb-md-0">
                    <h5 class="mb-4">خدماتنا</h5>
                    <div class="footer-links">
                        <p><a href="#">تسليم الطرود</a></p>
                        <p><a href="#">طلبات المطاعم</a></p>
                        <p><a href="#">تسليم المستندات</a></p>
                        <p><a href="#">تسليم الهدايا</a></p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4">
                    <h5 class="mb-4">معلومات الاتصال</h5>
                    <div class="footer-links">
                        <p class="mb-3">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            حماة، سوريا
                        </p>
                        <p class="mb-3">
                            <i class="fas fa-phone me-2"></i>
                            +963 123 456 789
                        </p>
                        <p class="mb-3">
                            <i class="fas fa-envelope me-2"></i>
                            info@motogo.com
                        </p>
                        <p class="mb-3">
                            <i class="fas fa-clock me-2"></i>
                            ‏من السبت إلى الخميس: 8 صباحاً - 10 مساءً
                        </p>
                    </div>
                </div>
            </div>
            <hr class="mt-5 mb-4" style="border-color: rgba(255,255,255,0.1);">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">© 2023 موتو جو. جميع الحقوق محفوظة.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <div class="footer-links">
                        <a href="#" class="me-3">سياسة الخصوصية</a>
                        <a href="#">شروط الاستخدام</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- Bootstrap & jQuery JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script>
        // Sidebar toggle functionality
        $(document).ready(function() {
            const sidebar = $('#sidebar');
            const sidebarOverlay = $('#sidebarOverlay');
            const menuToggle = $('#menuToggle');

            // Toggle sidebar
            menuToggle.on('click', function() {
                sidebar.toggleClass('open');
                sidebarOverlay.toggleClass('show');
            });

            // Close sidebar when clicking on overlay
            sidebarOverlay.on('click', function() {
                sidebar.removeClass('open');
                sidebarOverlay.removeClass('show');
            });

            // Auto-close sidebar when clicking on links (mobile)
            $(document).on('click', '.sidebar-nav a', function() {
                if ($(window).width() < 992) {
                    sidebar.removeClass('open');
                    sidebarOverlay.removeClass('show');
                }
            });

            // Smooth scroll for anchor links
            $('a[href^="#"]').on('click', function(e) {
                if (this.hash !== "") {
                    e.preventDefault();
                    const target = $(this.hash);
                    if (target.length) {
                        $('html, body').animate({
                            scrollTop: target.offset().top - 80
                        }, 800);
                    }
                }
            });
        });
    </script>
</body>

</html>
