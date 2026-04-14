<!DOCTYPE html>
<html lang="vi" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Z-PHIM 3D Dashboard - Trung tâm quản trị</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom 3D CSS -->
    <link rel="stylesheet" href="{{ asset('css/3d.css') }}">
    
    <style>
        * {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        html {
            scroll-behavior: smooth;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(15, 23, 42, 0.5);
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #6366f1, #8b5cf6);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #8b5cf6, #06b6d4);
        }

        .stat-card {
            animation: slideInUp 0.6s ease-out forwards;
        }

        .stat-card:nth-child(1) { animation-delay: 0.1s; }
        .stat-card:nth-child(2) { animation-delay: 0.2s; }
        .stat-card:nth-child(3) { animation-delay: 0.3s; }
        .stat-card:nth-child(4) { animation-delay: 0.4s; }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800">
    <!-- 3D Canvas Background -->
    <div id="canvas-container"></div>

    <!-- Navigation Bar -->
    <nav class="fixed top-0 left-0 right-0 z-50 backdrop-blur-xl border-b border-indigo-500/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center gap-8">
                    <a href="/" class="text-2xl font-black">
                        <span class="bg-gradient-to-r from-indigo-400 to-purple-500 bg-clip-text text-transparent">Z-PHIM</span>
                    </a>
                    <div class="hidden sm:flex items-center gap-6">
                        <a href="{{ route('admin.dashboard') }}" class="text-indigo-400 hover:text-indigo-300 text-sm font-semibold transition-colors">Dashboard</a>
                        <a href="{{ route('admin.movies.index') }}" class="text-slate-400 hover:text-white text-sm font-semibold transition-colors">Phim</a>
                        <a href="{{ route('admin.showtimes.index') }}" class="text-slate-400 hover:text-white text-sm font-semibold transition-colors">Suất chiếu</a>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-3 px-4 py-2 rounded-full bg-indigo-500/10 border border-indigo-500/20">
                        <div class="w-8 h-8 rounded-full bg-indigo-500/30 flex items-center justify-center text-indigo-400 font-bold text-xs">
                            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                        </div>
                        <span class="text-sm font-semibold text-slate-200">{{ auth()->user()->name ?? 'Admin' }}</span>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="btn-3d !px-4 !py-2 text-xs">Đăng xuất</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-3d pt-32">
        <!-- Header -->
        <div class="header-3d mb-16">
            <h1 class="text-5xl md:text-6xl font-black mb-4">TRUNG TÂM QUẢN TRỊ 3D</h1>
            <p class="text-xl text-slate-400 font-light">Giám sát hệ thống rạp phim với công nghệ ba chiều</p>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid mb-16">
            <!-- Total Movies -->
            <div class="card-3d stat-card">
                <div class="stat-icon">
                    <svg class="w-10 h-10 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16m10-16v16M4 4h16m0 16H4"></path>
                    </svg>
                </div>
                <p class="stat-label mb-2">Tổng phim</p>
                <p class="stat-number" data-target="{{ $stats['movies'] ?? 0 }}">0</p>
                <p class="text-sm text-slate-500 font-medium">trong thư viện</p>
            </div>

            <!-- Active Showtimes -->
            <div class="card-3d stat-card">
                <div class="stat-icon">
                    <svg class="w-10 h-10 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="stat-label mb-2">Suất chiếu</p>
                <p class="stat-number" data-target="{{ $stats['showtimes'] ?? 0 }}">0</p>
                <p class="text-sm text-slate-500 font-medium">đang hoạt động</p>
            </div>

            <!-- Total Bookings -->
            <div class="card-3d stat-card">
                <div class="stat-icon">
                    <svg class="w-10 h-10 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m-4 3v2m4 3v2M9 5l1 12a2 2 0 002 2h2a2 2 0 002-2l1-12M9 5a2 2 0 002-2h2a2 2 0 012 2m0 0H7m6 4l-1 8m-4-8l1 8"></path>
                    </svg>
                </div>
                <p class="stat-label mb-2">Đặt chỗ</p>
                <p class="stat-number" data-target="{{ $stats['bookings'] ?? 0 }}">0</p>
                <p class="text-sm text-slate-500 font-medium">tổng cộng</p>
            </div>

            <!-- Total Revenue -->
            <div class="card-3d stat-card">
                <div class="stat-icon">
                    <svg class="w-10 h-10 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="stat-label mb-2">Doanh thu</p>
                <p class="stat-number" data-target="{{ intval($stats['total_revenue'] ?? 0) }}">$0</p>
                <p class="text-sm text-slate-500 font-medium">tổng cộng</p>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid md:grid-cols-2 gap-8 mb-16">
            <!-- Status Card -->
            <div class="card-3d p-8 h-80">
                <h2 class="text-2xl font-bold text-white mb-2">Trạng thái hệ thống</h2>
                <p class="text-slate-400 text-sm mb-8">Giám sát tình trạng hoạt động</p>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 rounded-lg bg-slate-800/50 border border-slate-700">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span class="text-slate-300 font-medium">Dịch vụ chính</span>
                        </div>
                        <span class="text-emerald-400 font-bold text-sm">ONLINE</span>
                    </div>

                    <div class="flex items-center justify-between p-4 rounded-lg bg-slate-800/50 border border-slate-700">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span class="text-slate-300 font-medium">Cơ sở dữ liệu</span>
                        </div>
                        <span class="text-emerald-400 font-bold text-sm">ONLINE</span>
                    </div>

                    <div class="flex items-center justify-between p-4 rounded-lg bg-slate-800/50 border border-slate-700">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span class="text-slate-300 font-medium">API Gateway</span>
                        </div>
                        <span class="text-emerald-400 font-bold text-sm">ONLINE</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card-3d p-8 h-80">
                <h2 class="text-2xl font-bold text-white mb-2">Hành động nhanh</h2>
                <p class="text-slate-400 text-sm mb-8">Quản lý các tác vụ chính</p>
                
                <div class="space-y-3">
                    <a href="{{ route('admin.movies.create') }}" class="btn-3 w-full !justify-center flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Thêm phim mới
                    </a>
                    <a href="{{ route('admin.showtimes.create') }}" class="btn-3d w-full !justify-center flex items-center gap-2 bg-purple-600 hover:bg-purple-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tạo suất chiếu
                    </a>
                    <a href="{{ route('admin.bookings.index') }}" class="btn-3d w-full !justify-center flex items-center gap-2 bg-cyan-600 hover:bg-cyan-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m0 0l8-4m-8 4v10l8 4m0-10l8 4m-8-4v10"></path>
                        </svg>
                        Xem đặt chỗ
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="card-3d p-8 mb-16">
            <h2 class="text-2xl font-bold text-white mb-6">Hoạt động gần đây</h2>
            
            <div class="space-y-4">
                <div class="flex items-center gap-4 p-4 rounded-lg hover:bg-slate-800/50 transition-colors">
                    <div class="w-12 h-12 rounded-lg bg-indigo-500/20 flex items-center justify-center text-indigo-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-white font-medium">Phim mới được thêm</p>
                        <p class="text-sm text-slate-400">{{ now()->diffForHumans() }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-4 p-4 rounded-lg hover:bg-slate-800/50 transition-colors">
                    <div class="w-12 h-12 rounded-lg bg-purple-500/20 flex items-center justify-center text-purple-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-white font-medium">Suất chiếu được cập nhật</p>
                        <p class="text-sm text-slate-400">{{ now()->subHours(2)->diffForHumans() }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-4 p-4 rounded-lg hover:bg-slate-800/50 transition-colors">
                    <div class="w-12 h-12 rounded-lg bg-cyan-500/20 flex items-center justify-center text-cyan-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-white font-medium">Đặt chỗ mới được xử lý</p>
                        <p class="text-sm text-slate-400">{{ now()->subMinutes(15)->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/3d-scene.js') }}"></script>
    <script>
        // Additional dashboard interactivity
        document.querySelectorAll('.stat-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.boxShadow = '0 20px 40px rgba(99, 102, 241, 0.3)';
            });
            card.addEventListener('mouseleave', function() {
                this.style.boxShadow = 'none';
            });
        });
    </script>
</body>
</html>
