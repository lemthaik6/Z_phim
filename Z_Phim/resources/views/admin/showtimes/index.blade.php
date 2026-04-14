@extends('layouts.app')

@section('content')
@include('admin.partials.nav')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8 flex items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-white">Quản lý suất chiếu</h1>
            <p class="mt-2 text-slate-400">Danh sách suất chiếu hiện có.</p>
        </div>
        <a href="{{ route('admin.showtimes.create') }}" class="btn-primary inline-flex items-center gap-2 px-5 py-3 rounded-2xl">
            Thêm suất chiếu
        </a>
    </div>

    @if(session('success'))
    <div class="mb-6 rounded-3xl bg-emerald-500/10 border border-emerald-500/20 p-4 text-emerald-200">
        {{ session('success') }}
    </div>
    @endif

    <div class="glass-card rounded-[2.5rem] overflow-hidden border border-white/10 bg-slate-950/70">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-700">
                <thead class="bg-slate-900/70">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Phim</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Rạp / Phòng</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Thời gian</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Giá</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Hành động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800 bg-slate-950/80">
                    @forelse($showtimes as $showtime)
                    <tr>
                        <td class="px-6 py-4 text-sm text-white">{{ $showtime->movie->title }}</td>
                        <td class="px-6 py-4 text-sm text-slate-300">
                            {{ $showtime->room->cinema->name ?? 'Rạp chưa xác định' }} - Phòng {{ $showtime->room->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-300">
                            {{ $showtime->start_time->format('d M Y H:i') }}<br>
                            <span class="text-xs text-slate-500">đến {{ $showtime->end_time->format('H:i') }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-white">${{ number_format($showtime->price, 2) }}</td>
                        <td class="px-6 py-4 text-sm">
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('admin.showtimes.show', $showtime) }}" class="text-indigo-400 hover:text-white">Xem</a>
                                <a href="{{ route('admin.showtimes.edit', $showtime) }}" class="text-sky-400 hover:text-white">Sửa</a>
                                <form method="POST" action="{{ route('admin.showtimes.destroy', $showtime) }}" onsubmit="return confirm('Xoá suất chiếu này?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-400 hover:text-white">Xoá</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-500">Không có suất chiếu nào.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-white/10 bg-slate-950/80">
            {{ $showtimes->links() }}
        </div>
    </div>
</div>
@endsection
