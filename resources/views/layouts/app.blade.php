<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DrizStuff - Your Trusted Marketplace')</title>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/homepage.css') }}">
    <link rel="stylesheet" href="{{ asset('css/product.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    @stack('styles')
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container">
            <div class="navbar-content">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="navbar-brand">
                    🛍️ DrizStuff
                </a>

                <!-- Search Bar (Desktop) -->
                <div class="navbar-search">
                    <form action="{{ route('home') }}" method="GET">
                        <div class="search-input-wrapper">
                            <input type="text"
                                name="search"
                                placeholder="Search products..."
                                value="{{ request('search') }}"
                                class="navbar-search-input">
                            <button type="submit" class="navbar-search-btn">
                                Cari
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Navigation Links -->
                <div class="navbar-links">
                    <a href="{{ route('home') }}" class="nav-link">
                        Home
                    </a>

                    @auth
                    <!-- Cart -->
                    <a href="{{ route('cart.index') }}" class="nav-link cart-link">
                        Cart
                        @if(session('cart') && count(session('cart')) > 0)
                        <span class="cart-badge">{{ count(session('cart')) }}</span>
                        @endif
                    </a>

                    <!-- Transactions -->
                    <a href="{{ route('transactions.index') }}" class="nav-link">
                        Orders
                    </a>

                    <!-- Dropdown Menu -->
                    <div class="navbar-dropdown">
                        <button class="nav-link dropdown-toggle">
                            👤 {{ Auth::user()->name }}
                        </button>
                        <div class="dropdown-menu">
                            <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                ⚙️ Profile
                            </a>

                            @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="dropdown-item">
                                🔧 Admin Dashboard
                            </a>
                            @endif

                            @if(!Auth::user()->isAdmin())
                                @if(Auth::user()->store)
                                    <a href="{{ route('seller.dashboard') }}" class="dropdown-item">
                                        🏪 Seller Dashboard
                                    </a>
                                @else
                                    <a href="{{ route('seller.register') }}" class="dropdown-item">
                                        🎯 Become a Seller
                                    </a>
                                @endif
                            @endif

                            <hr class="dropdown-divider">

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    🚪 Logout
                                </button>
                            </form>
                        </div>
                    </div>
                    @else
                    <a href="{{ route('login') }}" class="btn btn-outline btn-sm">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">
                        Register
                    </a>
                    @endauth
                </div>

                <!-- Mobile Menu Toggle -->
                <button class="navbar-toggler">
                    ☰
                </button>
            </div>
        </div>
    </nav>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="container mt-md">
        <div class="alert alert-success">
            ✅ {{ session('success') }}
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="container mt-md">
        <div class="alert alert-danger">
            ❌ {{ session('error') }}
        </div>
    </div>
    @endif

    @if(session('info'))
    <div class="container mt-md">
        <div class="alert alert-info">
            ℹ️ {{ session('info') }}
        </div>
    </div>
    @endif

    @if(session('warning'))
    <div class="container mt-md">
        <div class="alert alert-warning">
            ⚠️ {{ session('warning') }}
        </div>
    </div>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <!-- About -->
                <div class="footer-section">
                    <h3 class="footer-title">🛍️ DrizStuff</h3>
                    <p class="footer-text">
                        Your trusted online marketplace for quality products.
                        Shop with confidence from verified sellers.
                    </p>
                    <div class="social-links">
                        <a href="#" class="social-link">📘</a>
                        <a href="#" class="social-link">📷</a>
                        <a href="#" class="social-link">🐦</a>
                        <a href="#" class="social-link">💬</a>
                    </div>
                </div>

                <!-- Shop -->
                <div class="footer-section">
                    <h4 class="footer-title">Shop</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}">All Products</a></li>
                        <li><a href="{{ route('home') }}?category=electronics">Electronics</a></li>
                        <li><a href="{{ route('home') }}?category=fashion">Fashion</a></li>
                        <li><a href="{{ route('home') }}?category=books">Books</a></li>
                    </ul>
                </div>

                <!-- For Sellers -->
                <div class="footer-section">
                    <h4 class="footer-title">For Sellers</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('seller.register') }}">Register as Seller</a></li>
                        <li><a href="#">Seller Guide</a></li>
                        <li><a href="#">Success Stories</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div class="footer-section">
                    <h4 class="footer-title">Contact Us</h4>
                    <ul class="footer-links">
                        <li>📧 support@drizstuff.com</li>
                        <li>📞 +62 812-3456-7890</li>
                        <li>📍 Jakarta, Indonesia</li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; 2025 DrizStuff. All rights reserved. Made with ❤️ for great shopping experience.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Dropdown toggle
        document.querySelectorAll('.dropdown-toggle').forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                e.stopPropagation();
                const menu = this.nextElementSibling;
                menu.classList.toggle('show');
            });
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function() {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.remove('show');
            });
        });

        // Mobile menu toggle
        document.querySelector('.navbar-toggler')?.addEventListener('click', function() {
            document.querySelector('.navbar-links').classList.toggle('show');
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                alert.style.opacity = '0';
                alert.style.transition = 'opacity 0.5s';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>

    @stack('scripts')
</body>

</html>