@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-white">Đặt chỗ của tôi</h1>
        <p class="mt-2 text-slate-400">Xem lịch sử và quản lý các vé bạn đã đặt.</p>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-3xl border border-emerald-500/20 bg-emerald-500/10 p-4 text-emerald-200">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 rounded-3xl border border-rose-500/20 bg-rose-500/10 p-4 text-rose-200">
            {{ session('error') }}
        </div>
    @endif

    @if($bookings->count())
        <div class="grid gap-6">
            @foreach($bookings as $booking)
                <article class="glass-card rounded-[2rem] p-6 border border-white/10 hover:border-indigo-500/20 transition-all">
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6">
                        <div>
                            <h2 class="text-xl font-bold text-white">{{ $booking->showtime->movie->title ?? 'Đặt chỗ không xác định' }}</h2>
                            <p class="mt-2 text-sm text-slate-400">
                                {{ $booking->showtime ? \Carbon\Carbon::parse($booking->showtime->start_time)->format('l, d M Y H:i') : 'Không có thông tin suất chiếu' }}
                            </p>
                            <p class="text-sm text-slate-400 mt-1">
                                {{ $booking->showtime && $booking->showtime->room ? ($booking->showtime->room->cinema->name ?? 'Rạp') . ' - ' . $booking->showtime->room->name : 'Thông tin rạp chưa có' }}
                            </p>
                        </div>

                        <div class="text-right space-y-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold uppercase tracking-[0.2em] {{ in_array($booking->status, ['confirmed', 'paid']) ? 'bg-emerald-500/10 text-emerald-300 border border-emerald-500/15' : ($booking->status === 'pending' ? 'bg-amber-500/10 text-amber-300 border border-amber-500/15' : 'bg-rose-500/10 text-rose-300 border border-rose-500/15') }}">
                                {{ $booking->status === 'paid' ? 'Đã thanh toán' : ($booking->status === 'confirmed' ? 'Đã xác nhận' : ucfirst($booking->status)) }}
                            </span>
                            <p class="text-2xl font-black text-white">${{ number_format($booking->total_amount, 2) }}</p>
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-slate-300">
                        <div class="space-y-2">
                            <div class="text-slate-500 uppercase tracking-[0.25em] text-[10px] font-bold">Ghế</div>
                            <div class="flex flex-wrap gap-2">
                                @foreach($booking->seats as $seat)
                                    <span class="inline-flex items-center rounded-full bg-slate-800 px-3 py-1 text-xs text-slate-200 border border-white/10">
                                        Hàng {{ $seat->row_number }}, Ghế {{ $seat->column_number }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        <div class="space-y-2">
                            <div class="text-slate-500 uppercase tracking-[0.25em] text-[10px] font-bold">Thanh toán</div>
                            <p>{{ $booking->payments->first()?->payment_method ? ucfirst($booking->payments->first()->payment_method) : 'Chưa thanh toán' }}</p>
                            <p>{{ $booking->payments->first()?->status ? ucfirst($booking->payments->first()->status) : 'Chưa có giao dịch' }}</p>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                        <a href="{{ route('bookings.show', $booking) }}" class="btn-secondary w-full sm:w-auto !py-3 rounded-2xl text-center">Xem chi tiết</a>
                        @if(in_array($booking->status, ['pending', 'confirmed', 'paid']))
                            <form method="POST" action="{{ route('bookings.cancel', $booking) }}" class="w-full sm:w-auto">
                                @csrf
                                <button type="submit" class="btn-danger w-full sm:w-auto !py-3 rounded-2xl">Hủy đặt chỗ</button>
                            </form>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-10">
            {{ $bookings->links() }}
        </div>
    @else
        <div class="glass-card rounded-[2rem] p-12 text-center border border-white/10">
            <p class="text-slate-400">Bạn chưa có đặt chỗ nào. Hãy chọn phim và ghế để bắt đầu.</p>
            <a href="{{ route('movies.index') }}" class="mt-6 inline-flex items-center justify-center rounded-2xl bg-indigo-500 px-6 py-3 text-sm font-semibold text-white hover:bg-indigo-400 transition-colors">Khám phá phim</a>
        </div>
    @endif
</div>
@endsection