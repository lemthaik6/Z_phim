@extends('layouts.app')

@section('content')
@include('admin.partials.nav')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-12">
        <a href="{{ route('admin.movies.index') }}" class="text-indigo-400 hover:text-indigo-300 transition-colors flex items-center space-x-2 text-sm font-bold uppercase tracking-widest mb-6">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span>Quay lại danh sách</span>
        </a>

        <div class="flex flex-col md:flex-row gap-12">
            <div class="w-full md:w-1/3">
                <div class="glass-card rounded-[2.5rem] overflow-hidden">
                    <img src="{{ $movie->poster_url ?? 'https://via.placeholder.com/600x900?text=Kh%C3%B4ng+c%C3%B3+poster' }}" alt="{{ $movie->title }}" class="w-full h-auto">
                </div>
            </div>
            
            <div class="flex-1">
                <h1 class="text-5xl font-black text-white mb-6 uppercase tracking-tighter">{{ $movie->title }}</h1>
                
                <div class="flex flex-wrap gap-4 mb-8">
                    @foreach($movie->genres as $genre)
                        <span class="px-4 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-xs font-black uppercase tracking-widest">
                            {{ $genre->name }}
                        </span>
                    @endforeach
                </div>

                <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                    <div>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Thời lượng</p>
                        <p class="text-xl font-bold text-white">{{ $movie->duration }} phút</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Ngày phát hành</p>
                        <p class="text-xl font-bold text-white">{{ \Carbon\Carbon::parse($movie->release_date)->format('M d, Y') }}</p>
                    </div>
                </div>

                <div class="mb-12">
                    <h3 class="text-xs font-bold text-indigo-400 uppercase tracking-widest mb-4">Tóm tắt</h3>
                    <p class="text-slate-400 leading-relaxed text-lg">
                        {{ $movie->description }}
                    </p>
                </div>

                <div class="flex space-x-4">
                    <a href="{{ route('admin.movies.edit', $movie) }}" class="btn-primary !px-8 !py-4 !rounded-2xl">
                        Chỉnh sửa phim
                    </a>
                    <form action="{{ route('admin.movies.destroy', $movie) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xoá vĩnh viễn?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-8 py-4 rounded-2xl border border-rose-500/30 text-rose-500 hover:bg-rose-500/10 transition-colors font-bold uppercase tracking-widest text-sm">
                            Xoá
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
