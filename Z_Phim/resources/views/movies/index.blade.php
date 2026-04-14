@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-12">
        <h1 class="text-4xl font-extrabold text-white tracking-tight mb-2">Danh mục phim</h1>
        <p class="text-slate-400">Khám phá và đặt vé những bộ phim mới nhất</p>
    </div>

    <div id="movies-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        <!-- Phim sẽ được tải tại đây -->
    </div>

    <div id="loading" class="flex flex-col items-center justify-center py-20">
        <div class="relative w-16 h-16">
            <div class="absolute inset-0 border-4 border-indigo-500/20 rounded-full"></div>
            <div class="absolute inset-0 border-4 border-indigo-500 border-t-transparent rounded-full animate-spin"></div>
        </div>
        <p class="mt-4 text-slate-400 font-medium">Đang chọn phim cho bạn...</p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const moviesContainer = document.getElementById('movies-container');
    const loading = document.getElementById('loading');

    fetch('/api/movies', {
        credentials: 'same-origin',
        headers: {
            'X-CSRF-TOKEN': window.Laravel.csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        loading.style.display = 'none';

        if (data.data && data.data.length > 0) {
            data.data.forEach(movie => {
                const movieCard = `
                    <div class="group relative bg-slate-900 rounded-3xl overflow-hidden border border-white/5 hover:border-indigo-500/50 transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:shadow-indigo-500/20">
                        <div class="aspect-[2/3] overflow-hidden relative">
                            ${movie.poster_url 
                                ? `<img src="${movie.poster_url}" alt="${movie.title}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">` 
                                : '<div class="w-full h-full bg-slate-800 flex items-center justify-center text-slate-600 italic">Không có poster</div>'}
                            
                            <!-- Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/20 to-transparent opacity-80"></div>
                            
                            <!-- Duration Tag -->
                            <div class="absolute top-4 right-4 px-3 py-1 rounded-full bg-slate-950/80 backdrop-blur-md border border-white/10 text-[10px] font-bold text-slate-300 uppercase tracking-widest">
                                ${movie.duration} phút
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-white mb-2 line-clamp-1 group-hover:text-indigo-400 transition-colors">${movie.title}</h3>
                            <p class="text-sm text-slate-400 mb-6 line-clamp-2 h-10 leading-relaxed">${movie.description}</p>
                            
                            <a href="/movies/${movie.id}" class="btn-primary w-full !py-3 flex items-center justify-center space-x-2">
                                <span class="text-sm">Mua vé</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5l5 5-5 5M4 4h16m-7 8h7m-7 4h7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                `;
                moviesContainer.innerHTML += movieCard;
            });
        } else {
            moviesContainer.innerHTML = `
                <div class="col-span-full py-20 text-center glass-card rounded-3xl">
                    <p class="text-slate-500">Hiện chưa có phim.</p>
                </div>
            `;
        }
    })
    .catch(error => {
        loading.style.display = 'none';
        console.error('Error loading movies:', error);
        moviesContainer.innerHTML = '<p class="text-center text-red-400 col-span-full">Đã có lỗi xảy ra. Vui lòng tải lại trang.</p>';
    });
});
</script>
@endsection
