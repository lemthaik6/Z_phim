<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <!-- Modern Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased selection:bg-indigo-500/30">
    <nav class="fixed top-0 left-0 right-0 z-50 glass-nav">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="/" class="text-2xl font-extrabold tracking-tight text-white group">
                            <span class="bg-clip-text text-transparent bg-gradient-to-r from-indigo-400 to-purple-400 group-hover:from-indigo-300 group-hover:to-purple-300 transition-all">Z-PHIM</span>
                        </a>
                    </div>
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <a href="{{ route('movies.index') }}" class="text-slate-300 hover:text-white transition-colors px-1 pt-1 text-sm font-medium">Phim</a>
                        <div class="auth-only hidden items-center space-x-8">
                            <a href="{{ route('bookings.index') }}" class="text-slate-300 hover:text-white transition-colors px-1 pt-1 text-sm font-medium">Đặt chỗ của tôi</a>
                            @if(auth()->check() && auth()->user()->is_admin)
                                <a href="{{ route('admin.dashboard') }}" class="text-indigo-400 hover:text-indigo-300 transition-colors px-1 pt-1 text-sm font-medium">Bảng điều khiển</a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="hidden sm:flex sm:items-center sm:ml-6">
                    <div class="auth-only hidden items-center space-x-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-full bg-indigo-500/20 border border-indigo-500/50 flex items-center justify-center text-indigo-400 text-xs font-bold">
                                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                            </div>
                            <span class="text-sm font-medium text-slate-200">{{ auth()->user()->name ?? 'Người dùng' }}</span>
                        </div>
                        <button onclick="logout()" class="text-sm font-medium text-slate-400 hover:text-white transition-colors">Đăng xuất</button>
                    </div>
                    <div class="guest-only hidden items-center space-x-4">
                        <a href="{{ route('login') }}" class="text-sm font-medium text-slate-300 hover:text-white transition-colors">Đăng nhập</a>
                        <a href="{{ route('register') }}" class="btn-primary !py-2 !px-5 text-sm">Đăng ký</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="pt-24 pb-12 min-h-screen relative overflow-hidden">
        <!-- Background decorative elements -->
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-indigo-600/10 rounded-full blur-[120px] -z-10"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-purple-600/10 rounded-full blur-[120px] -z-10"></div>
        
        @yield('content')
    </main>

    <footer class="border-t border-white/5 py-12 bg-slate-950">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-slate-500 text-sm">© {{ date('Y') }} Z-PHIM. Trải nghiệm rạp chiếu ngay trong tầm tay.</p>
        </div>
    </footer>

    <script>
        window.Laravel = {
            csrfToken: "{{ csrf_token() }}",
            apiToken: "{{ session('api_token', '') }}"
        };

        document.addEventListener('DOMContentLoaded', function() {
            updateAuthUI({{ auth()->check() ? 'true' : 'false' }});
        });

        function updateAuthUI(isLoggedIn) {
            const guestElements = document.querySelectorAll('.guest-only');
            const authElements = document.querySelectorAll('.auth-only');

            if (isLoggedIn) {
                guestElements.forEach(el => {
                    el.classList.add('hidden');
                    el.classList.remove('flex');
                });
                authElements.forEach(el => {
                    el.classList.remove('hidden');
                    el.classList.add('flex');
                });
            } else {
                guestElements.forEach(el => {
                    el.classList.remove('hidden');
                    el.classList.add('flex');
                });
                authElements.forEach(el => {
                    el.classList.add('hidden');
                    el.classList.remove('flex');
                });
            }
        }

        function logout() {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('logout') }}';
            form.innerHTML = `<input type="hidden" name="_token" value="${window.Laravel.csrfToken}">`;
            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>
</html>
