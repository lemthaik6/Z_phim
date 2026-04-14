@extends('layouts.app')

@section('content')
<div class="relative min-h-[50vh] -mt-24 mb-12 flex items-end">
    <!-- Backdrop Background -->
    <div class="absolute inset-0 overflow-hidden">
        @if($movie->poster_url)
            <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" class="w-full h-full object-cover opacity-30 blur-2xl scale-110">
        @else
            <div class="w-full h-full bg-slate-900"></div>
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full relative pb-12">
        <div class="flex flex-col md:flex-row items-end gap-10">
            <!-- Poster -->
            <div class="flex-shrink-0 relative group">
                <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-[2rem] blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200"></div>
                @if($movie->poster_url)
                    <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" class="relative w-64 md:w-80 h-auto rounded-3xl shadow-2xl border border-white/10">
                @else
                    <div class="relative w-64 md:w-80 h-96 bg-slate-800 rounded-3xl flex items-center justify-center text-slate-500 italic border border-white/5">Không có poster</div>
                @endif
            </div>

            <!-- Basic Info -->
            <div class="flex-1 pb-4">
                <div class="flex flex-wrap items-center gap-3 mb-4">
                    <span class="px-3 py-1 rounded-full bg-indigo-500/20 text-indigo-400 text-xs font-bold uppercase tracking-widest border border-indigo-500/30">
                        {{ $movie->duration }} phút
                    </span>
                    <span class="px-3 py-1 rounded-full bg-slate-800/80 text-slate-300 text-xs font-bold uppercase tracking-widest border border-white/5">
                        Phát hành {{ \Carbon\Carbon::parse($movie->release_date)->year }}
                    </span>
                </div>
                
                <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-4 tracking-tight">{{ $movie->title }}</h1>
                
                <p class="text-lg text-slate-400 max-w-3xl leading-relaxed mb-6">
                    {{ $movie->description }}
                </p>

                @if($movie->genres && count($movie->genres) > 0)
                <div class="flex flex-wrap gap-2">
                    @foreach($movie->genres as $genre)
                    <span class="px-4 py-1.5 rounded-xl bg-white/5 border border-white/10 text-sm font-medium text-slate-300 hover:bg-white/10 transition-colors cursor-default">
                        {{ $genre->name }}
                    </span>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
    <div class="mb-10 flex items-center justify-between">
        <h2 class="text-3xl font-bold text-white tracking-tight">Suất chiếu hiện có</h2>
        <div class="h-px flex-1 mx-8 bg-gradient-to-r from-white/10 to-transparent hidden md:block"></div>
    </div>

    <div id="showtimes-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Suất chiếu sẽ được tải tại đây -->
    </div>

    <div id="showtimes-loading" class="flex flex-col items-center justify-center py-20">
        <div class="relative w-12 h-12">
            <div class="absolute inset-0 border-3 border-indigo-500/20 rounded-full"></div>
            <div class="absolute inset-0 border-3 border-indigo-500 border-t-transparent rounded-full animate-spin"></div>
        </div>
        <p class="mt-4 text-slate-500 font-medium">Đang tải lịch chiếu...</p>
    </div>

    <div class="mt-16 glass-card rounded-3xl p-8 border border-white/10 bg-slate-950/80">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
            <div>
                <h2 class="text-3xl font-bold text-white">Đánh giá & bình luận</h2>
                <p class="text-slate-400 mt-2">Xem nhận xét của khán giả và để lại cảm nhận của bạn về phim.</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="px-4 py-3 rounded-3xl bg-slate-950/70 border border-white/10">
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Điểm trung bình</p>
                    <p class="text-3xl font-black text-white mt-1">{{ $movie->reviews->avg('rating') ? number_format($movie->reviews->avg('rating'), 1) : '0.0' }}</p>
                </div>
                <div class="px-4 py-3 rounded-3xl bg-slate-950/70 border border-white/10">
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-500">Số nhận xét</p>
                    <p class="text-3xl font-black text-white mt-1">{{ $movie->reviews->count() }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-10">
            <div class="lg:col-span-2 space-y-6">
                @forelse($movie->reviews as $review)
                    <div class="p-6 rounded-3xl bg-slate-950/80 border border-white/10">
                        <div class="flex items-start justify-between gap-4 mb-4">
                            <div>
                                <p class="text-sm font-semibold text-white">{{ $review->user->name ?? 'Người dùng' }}</p>
                                <p class="text-xs text-slate-500">{{ $review->created_at->format('d M Y') }}</p>
                            </div>
                            <span class="inline-flex items-center rounded-full bg-indigo-500/10 px-3 py-1 text-sm font-semibold text-indigo-300">{{ $review->rating }} / 5</span>
                        </div>
                        <p class="text-slate-300 leading-relaxed">{{ $review->comment }}</p>
                    </div>
                @empty
                    <div class="p-6 rounded-3xl bg-slate-950/80 border border-white/10 text-slate-500">
                        Chưa có bình luận nào cho phim này. Hãy là người đầu tiên chia sẻ cảm nhận.
                    </div>
                @endforelse
            </div>

            <div class="space-y-6">
                <div class="p-6 rounded-3xl bg-slate-950/80 border border-white/10">
                    <h3 class="text-xl font-bold text-white mb-4">Gửi bình luận</h3>
                    <form method="POST" action="{{ route('movies.reviews.store', $movie) }}">
                        @csrf
                        <label class="block text-sm text-slate-300 mb-2">Điểm của bạn</label>
                        <select name="rating" class="w-full rounded-2xl border border-white/10 bg-slate-900 px-4 py-3 text-white focus:border-indigo-500 focus:outline-none">
                            @for($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}">{{ $i }} sao</option>
                            @endfor
                        </select>

                        <label class="block text-sm text-slate-300 mt-5 mb-2">Bình luận</label>
                        <textarea name="comment" rows="6" class="w-full rounded-3xl border border-white/10 bg-slate-900 px-4 py-4 text-white focus:border-indigo-500 focus:outline-none" placeholder="Viết cảm nhận của bạn..."></textarea>

                        @if($errors->any())
                            <div class="mt-4 rounded-2xl bg-rose-500/10 border border-rose-500/20 p-4 text-rose-200 text-sm">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <button type="submit" class="mt-6 btn-primary w-full !py-4 rounded-3xl font-bold">Gửi đánh giá</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const showtimesContainer = document.getElementById('showtimes-container');
    const loading = document.getElementById('showtimes-loading');
    const movieId = {{ $movie->id }};

    fetch('/api/showtimes', {
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
            const movieShowtimes = data.data.filter(showtime => showtime.movie_id === movieId);

            if (movieShowtimes.length > 0) {
                movieShowtimes.forEach(showtime => {
                    const startTime = new Date(showtime.start_time);
                    const endTime = new Date(showtime.end_time);

                    const showtimeCard = `
                        <div class="glass-card rounded-3xl p-6 hover:bg-slate-900/40 transition-all group flex flex-col justify-between h-full">
                            <div>
                                <div class="flex justify-between items-start mb-6">
                                    <div class="p-3 rounded-2xl bg-indigo-500/10 text-indigo-400 group-hover:scale-110 transition-transform">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-2xl font-black text-white group-hover:text-indigo-400 transition-colors">$${showtime.price}</p>
                                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Mỗi ghế</p>
                                    </div>
                                </div>
                                
                                <div class="mb-6">
                                    <p class="text-sm font-bold text-indigo-400 mb-1 tracking-wide">${startTime.toLocaleDateString([], {weekday: 'long', month: 'short', day: 'numeric'})}</p>
                                    <p class="text-xl font-extrabold text-white">
                                        ${startTime.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})} 
                                            <span class="text-slate-500 font-medium text-sm mx-1">đến</span> 
                                        ${endTime.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}
                                    </p>
                                </div>

                                <div class="flex items-center space-x-3 mb-8 p-3 rounded-2xl bg-white/5 border border-white/5">
                                    <div class="w-2 h-2 rounded-full bg-indigo-500 shadow-[0_0_8px_rgba(99,102,241,0.8)]"></div>
                                    <p class="text-sm font-medium text-slate-300">
                                        ${showtime.room ? `${showtime.room.cinema ? showtime.room.cinema.name : 'Rạp'} - Phòng ${showtime.room.name}` : 'Rạp chiếu đặc biệt'}
                                    </p>
                                </div>
                            </div>

                            <a href="/movies/${movieId}/showtimes/${showtime.id}/seats"
                               class="btn-primary !py-4 flex items-center justify-center space-x-3 group/btn">
                                <span class="font-bold">Đặt suất chiếu này</span>
                                <svg class="w-5 h-5 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </a>
                        </div>
                    `;
                    showtimesContainer.innerHTML += showtimeCard;
                });
            } else {
                showtimesContainer.innerHTML = `
                    <div class="col-span-full py-20 text-center glass-card rounded-3xl">
                        <p class="text-slate-500">Hiện chưa có suất chiếu cho phim này.</p>
                    </div>
                `;
            }
        } else {
            showtimesContainer.innerHTML = `
                <div class="col-span-full py-20 text-center glass-card rounded-3xl">
                    <p class="text-slate-500">Không có lịch chiếu.</p>
                </div>
            `;
        }
    })
    .catch(error => {
        loading.style.display = 'none';
        console.error('Error loading showtimes:', error);
        showtimesContainer.innerHTML = '<p class="text-center text-red-400 col-span-full">Không tải được lịch chiếu. Vui lòng thử lại sau.</p>';
    });
});
</script>
@endsection
