<!DOCTYPE html>
<html lang="vi" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Z-PHIM 3D - Danh mục phim ba chiều</title>
    
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

        .movie-card-3d {
            animation: popIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
            opacity: 0;
        }

        .movie-card-3d:nth-child(1) { animation-delay: 0s; }
        .movie-card-3d:nth-child(2) { animation-delay: 0.05s; }
        .movie-card-3d:nth-child(3) { animation-delay: 0.1s; }
        .movie-card-3d:nth-child(4) { animation-delay: 0.15s; }
        .movie-card-3d:nth-child(5) { animation-delay: 0.2s; }
        .movie-card-3d:nth-child(n+6) { animation-delay: 0.25s; }

        @keyframes popIn {
            from {
                opacity: 0;
                transform: scale(0.5) rotateX(-20deg);
            }
            to {
                opacity: 1;
                transform: scale(1) rotateX(0);
            }
        }

        .movie-filter {
            animation: slideDown 0.4s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .filter-btn {
            @apply px-4 py-2 rounded-lg border border-slate-700 text-slate-400 hover:text-white hover:border-indigo-500 transition-all cursor-pointer;
        }

        .filter-btn.active {
            @apply bg-indigo-500 border-indigo-500 text-white;
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
                        <a href="{{ route('movies.index') }}" class="text-indigo-400 hover:text-indigo-300 text-sm font-semibold transition-colors">Phim</a>
                        @if(auth()->check())
                            <a href="{{ route('bookings.index') }}" class="text-slate-400 hover:text-white text-sm font-semibold transition-colors">Đặt chỗ của tôi</a>
                            @if(auth()->user()->is_admin)
                                <a href="{{ route('admin.dashboard') }}" class="text-slate-400 hover:text-white text-sm font-semibold transition-colors">Dashboard</a>
                            @endif
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    @if(auth()->check())
                        <div class="flex items-center gap-3 px-4 py-2 rounded-full bg-indigo-500/10 border border-indigo-500/20">
                            <div class="w-8 h-8 rounded-full bg-indigo-500/30 flex items-center justify-center text-indigo-400 font-bold text-xs">
                                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                            </div>
                            <span class="text-sm font-semibold text-slate-200">{{ auth()->user()->name ?? 'Người dùng' }}</span>
                        </div>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="btn-3d !px-4 !py-2 text-xs">Đăng xuất</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-slate-400 hover:text-white text-sm font-semibold">Đăng nhập</a>
                        <a href="{{ route('register') }}" class="btn-3d !px-4 !py-2 text-xs">Đăng ký</a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-3d pt-32">
        <!-- Header -->
        <div class="header-3d mb-16">
            <h1 class="text-5xl md:text-6xl font-black mb-4">BỘ SƯU TẬP PHIM 3D</h1>
            <p class="text-xl text-slate-400 font-light">Khám phá những bộ phim mới nhất với giao diện ba chiều tương tác</p>
        </div>

        <!-- Filter Section -->
        <div class="movie-filter mb-12 p-6 card-3d">
            <h3 class="text-white font-bold mb-4">Lọc phim theo thể loại</h3>
            <div class="flex flex-wrap gap-3">
                <button class="filter-btn active" onclick="filterMovies('all')">Tất cả</button>
                <button class="filter-btn" onclick="filterMovies('Action')">Action</button>
                <button class="filter-btn" onclick="filterMovies('Sci-Fi')">Sci-Fi</button>
                <button class="filter-btn" onclick="filterMovies('Drama')">Drama</button>
            </div>
        </div>

        <!-- Movies Grid -->
        <div id="movies-container" class="movies-grid mb-16">
            <!-- Phim sẽ được tải tại đây -->
        </div>

        <!-- Loading State -->
        <div id="loading" class="flex flex-col items-center justify-center py-20">
            <div class="loader-3d"></div>
            <p class="mt-8 text-slate-400 font-medium text-lg">Đang tải phim...</p>
        </div>

        <!-- Empty State -->
        <div id="empty-state" class="hidden text-center py-20">
            <div class="inline-block p-8 rounded-3xl bg-slate-800/50 border border-slate-700">
                <svg class="w-16 h-16 mx-auto text-slate-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16m10-16v16M4 4h16m0 16H4"></path>
                </svg>
                <p class="text-slate-400 text-lg font-medium">Không tìm thấy phim. Vui lòng thử lại.</p>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/3d-scene.js') }}"></script>
    <script>
        let allMovies = [];
        const moviesContainer = document.getElementById('movies-container');
        const loading = document.getElementById('loading');
        const emptyState = document.getElementById('empty-state');

        // Load movies from API
        document.addEventListener('DOMContentLoaded', function() {
            fetch('/api/movies', {
                credentials: 'same-origin',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                loading.style.display = 'none';
                allMovies = data.data || [];

                if (allMovies.length > 0) {
                    displayMovies(allMovies);
                } else {
                    emptyState.classList.remove('hidden');
                }
            })
            .catch(error => {
                loading.style.display = 'none';
                console.error('Error loading movies:', error);
                moviesContainer.innerHTML = '<p class="text-center text-red-400 col-span-full">Đã có lỗi xảy ra. Vui lòng tải lại trang.</p>';
            });
        });

        function displayMovies(movies) {
            moviesContainer.innerHTML = '';
            emptyState.classList.add('hidden');

            if (movies.length === 0) {
                emptyState.classList.remove('hidden');
                return;
            }

            movies.forEach((movie, index) => {
                const genres = movie.genres?.map(g => g.name).join(', ') || 'Chưa có thể loại';
                const movieCard = document.createElement('div');
                movieCard.className = 'movie-card-3d';
                movieCard.innerHTML = `
                    <div class="movie-poster relative overflow-hidden h-64">
                        ${movie.poster_url 
                            ? `<img src="${movie.poster_url}" alt="${movie.title}" class="w-full h-full object-cover" onerror="this.src='https://via.placeholder.com/250x350?text=${encodeURIComponent(movie.title)}'">`
                            : `<div class="w-full h-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                                <svg class="w-12 h-12 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16m10-16v16M4 4h16m0 16H4"></path>
                                </svg>
                            </div>`
                        }
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-transparent opacity-0 hover:opacity-100 transition-opacity duration-300 flex items-end p-4">
                            <div class="w-full">
                                <p class="text-white font-bold text-sm mb-2">${movie.duration} phút</p>
                                <p class="text-slate-400 text-xs line-clamp-1">${genres}</p>
                            </div>
                        </div>
                    </div>

                    <div class="movie-info">
                        <div>
                            <h3 class="movie-title">${movie.title}</h3>
                            <p class="movie-desc">${movie.description}</p>
                        </div>
                        <a href="/movies/${movie.id}" class="btn-3d w-full justify-center">
                            <span>Mua vé</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5M7 7l5 5m0-5L7 12"></path>
                            </svg>
                        </a>
                    </div>
                `;
                moviesContainer.appendChild(movieCard);

                // Add 3D interaction to card
                new MovieCard3D(movieCard);
            });
        }

        function filterMovies(genre) {
            // Update active button
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');

            // Filter and display movies
            if (genre === 'all') {
                displayMovies(allMovies);
            } else {
                const filtered = allMovies.filter(movie => {
                    return movie.genres?.some(g => g.name === genre);
                });
                displayMovies(filtered);
            }
        }

        // Enhanced MovieCard3D class for user page
        class MovieCard3D {
            constructor(cardElement) {
                this.card = cardElement;
                this.initListeners();
            }

            initListeners() {
                this.card.addEventListener('mousemove', (e) => this.onMouseMove(e));
                this.card.addEventListener('mouseleave', () => this.onMouseLeave());
            }

            onMouseMove(event) {
                const rect = this.card.getBoundingClientRect();
                const x = event.clientX - rect.left;
                const y = event.clientY - rect.top;

                const rotateX = (y - rect.height / 2) / 15;
                const rotateY = (x - rect.width / 2) / 15;

                this.card.style.transform = `perspective(1200px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateZ(10px)`;
            }

            onMouseLeave() {
                this.card.style.transform = 'perspective(1200px) rotateX(0) rotateY(0) translateZ(0)';
            }
        }
    </script>
</body>
</html>
