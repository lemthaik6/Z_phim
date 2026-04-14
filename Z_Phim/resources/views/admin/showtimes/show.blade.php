@extends('layouts.app')

@section('content')
@include('admin.partials.nav')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white">Chi tiết suất chiếu</h1>
            <p class="mt-2 text-slate-400">Thông tin chi tiết về suất chiếu và phim.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.showtimes.edit', $showtime) }}" class="btn-primary px-5 py-3 rounded-2xl">Sửa suất chiếu</a>
            <a href="{{ route('admin.showtimes.index') }}" class="btn-secondary px-5 py-3 rounded-2xl">Quay lại</a>
        </div>
    </div>

    <div class="glass-card rounded-[2.5rem] p-8 bg-slate-950/80 border border-white/10">
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
            <div class="space-y-6">
                <div>
                    <h2 class="text-sm uppercase tracking-widest text-indigo-400">Phim</h2>
                    <p class="mt-2 text-xl font-bold text-white">{{ $showtime->movie->title }}</p>
                </div>
                <div>
                    <h2 class="text-sm uppercase tracking-widest text-indigo-400">Rạp / phòng</h2>
                    <p class="mt-2 text-lg text-slate-200">{{ $showtime->room->cinema->name ?? 'Rạp chưa xác định' }} - Phòng {{ $showtime->room->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <h2 class="text-sm uppercase tracking-widest text-indigo-400">Thời gian</h2>
                    <p class="mt-2 text-lg text-slate-200">{{ $showtime->start_time->format('d M Y H:i') }} đến {{ $showtime->end_time->format('H:i') }}</p>
                </div>
            </div>

            <div class="space-y-6">
                <div>
                    <h2 class="text-sm uppercase tracking-widest text-indigo-400">Giá mỗi ghế</h2>
                    <p class="mt-2 text-3xl font-bold text-white">${{ number_format($showtime->price, 2) }}</p>
                </div>
                <div class="rounded-3xl bg-slate-900/70 p-6 border border-white/10">
                    <h3 class="text-sm uppercase tracking-widest text-slate-400">Trạng thái</h3>
                    <p class="mt-3 inline-flex rounded-full bg-indigo-500/10 px-3 py-2 text-sm font-semibold text-indigo-300">Hoạt động</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
