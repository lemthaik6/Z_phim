@extends('layouts.app')

@section('content')
@include('admin.partials.nav')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8 flex items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-white">Quản lý đặt chỗ</h1>
            <p class="mt-2 text-slate-400">Xem và quản lý tất cả đơn đặt chỗ.</p>
        </div>
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
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">#</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Người đặt</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Phim</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Ngày chiếu</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Tổng tiền</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Trạng thái</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Hành động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800 bg-slate-950/80">
                    @forelse($bookings as $booking)
                    <tr>
                        <td class="px-6 py-4 text-sm text-white">{{ $booking->id }}</td>
                        <td class="px-6 py-4 text-sm text-slate-300">{{ $booking->user->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-slate-300">{{ $booking->showtime->movie->title ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-slate-300">{{ $booking->showtime->start_time->format('d M Y H:i') }}</td>
                        <td class="px-6 py-4 text-sm text-white">${{ number_format($booking->total_amount, 2) }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-widest {{ $booking->status === 'confirmed' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : ($booking->status === 'pending' ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20') }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('admin.bookings.show', $booking) }}" class="text-indigo-400 hover:text-white">Xem</a>
                                <a href="{{ route('admin.bookings.edit', $booking) }}" class="text-sky-400 hover:text-white">Sửa</a>
                                <form method="POST" action="{{ route('admin.bookings.destroy', $booking) }}" onsubmit="return confirm('Xoá đặt chỗ này?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-400 hover:text-white">Xoá</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-slate-500">Không có đặt chỗ nào.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-white/10 bg-slate-950/80">
            {{ $bookings->links() }}
        </div>
    </div>
</div>
@endsection
