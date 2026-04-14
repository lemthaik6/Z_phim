@extends('layouts.app')

@section('content')
@include('admin.partials.nav')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white">Chi tiết đặt chỗ #{{ $booking->id }}</h1>
            <p class="mt-2 text-slate-400">Xem chi tiết đơn đặt chỗ và trạng thái thanh toán.</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.bookings.edit', $booking) }}" class="btn-primary px-5 py-3 rounded-2xl">Sửa trạng thái</a>
            <a href="{{ route('admin.bookings.index') }}" class="btn-secondary px-5 py-3 rounded-2xl">Quay lại</a>
        </div>
    </div>

    <div class="glass-card rounded-[2.5rem] p-8 bg-slate-950/80 border border-white/10 space-y-8">
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <div class="rounded-3xl bg-slate-900/60 p-6 border border-white/10">
                <p class="text-sm uppercase tracking-widest text-indigo-400 mb-3">Người đặt</p>
                <p class="text-xl font-bold text-white">{{ $booking->user->name }}</p>
                <p class="text-slate-400">{{ $booking->user->email }}</p>
            </div>
            <div class="rounded-3xl bg-slate-900/60 p-6 border border-white/10">
                <p class="text-sm uppercase tracking-widest text-indigo-400 mb-3">Tình trạng</p>
                <p class="text-2xl font-bold text-white">{{ ucfirst($booking->status) }}</p>
                <p class="mt-2 text-sm text-slate-400">Tổng: ${{ number_format($booking->total_amount, 2) }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="rounded-3xl bg-slate-900/60 p-6 border border-white/10">
                <p class="text-sm uppercase tracking-widest text-indigo-400 mb-3">Phim</p>
                <p class="text-lg font-bold text-white">{{ $booking->showtime->movie->title }}</p>
            </div>
            <div class="rounded-3xl bg-slate-900/60 p-6 border border-white/10">
                <p class="text-sm uppercase tracking-widest text-indigo-400 mb-3">Giờ chiếu</p>
                <p class="text-lg font-bold text-white">{{ $booking->showtime->start_time->format('d M Y H:i') }}</p>
                <p class="mt-1 text-slate-400">Kết thúc {{ $booking->showtime->end_time->format('H:i') }}</p>
            </div>
            <div class="rounded-3xl bg-slate-900/60 p-6 border border-white/10">
                <p class="text-sm uppercase tracking-widest text-indigo-400 mb-3">Rạp / Phòng</p>
                <p class="text-lg font-bold text-white">{{ $booking->showtime->room->cinema->name ?? 'Rạp chưa xác định' }}</p>
                <p class="mt-1 text-slate-400">Phòng {{ $booking->showtime->room->name ?? 'N/A' }}</p>
            </div>
        </div>

        <div class="rounded-3xl bg-slate-900/60 p-6 border border-white/10">
            <p class="text-sm uppercase tracking-widest text-indigo-400 mb-3">Ghế đã đặt</p>
            @if($booking->seats->count() > 0)
                <ul class="space-y-2 text-slate-300">
                    @foreach($booking->seats as $seat)
                        <li>Ghế {{ $seat->row }}{{ $seat->number }} - ${{ number_format($seat->pivot->price, 2) }}</li>
                    @endforeach
                </ul>
            @else
                <p class="text-slate-500">Chưa có ghế được gán.</p>
            @endif
        </div>
    </div>
</div>
@endsection
