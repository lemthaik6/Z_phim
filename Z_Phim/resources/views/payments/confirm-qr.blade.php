@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="glass-card rounded-[2.5rem] p-8 border border-white/10">
        <div class="mb-6">
            <h1 class="text-4xl font-extrabold text-white">Thanh toán giả lập</h1>
            <p class="mt-2 text-slate-400">Giao diện này dùng để mô phỏng chuyển sang cổng thanh toán khi đang chạy chế độ simulation.</p>
        </div>

        <div class="grid gap-6 lg:grid-cols-2 mb-8">
            <div class="rounded-3xl bg-slate-950/80 border border-white/10 p-6">
                <p class="text-sm uppercase tracking-widest text-indigo-400 font-bold mb-3">Đơn hàng</p>
                <p class="text-lg text-white font-semibold">#{{ str_pad($booking->id, 8, '0', STR_PAD_LEFT) }}</p>
                <p class="mt-2 text-sm text-slate-400">Phim: {{ $booking->showtime->movie->title }}</p>
                <p class="text-sm text-slate-400">Suất chiếu: {{ \Carbon\Carbon::parse($booking->showtime->start_time)->format('d M Y H:i') }}</p>
            </div>
            <div class="rounded-3xl bg-slate-950/80 border border-white/10 p-6">
                <p class="text-sm uppercase tracking-widest text-indigo-400 font-bold mb-3">Thanh toán</p>
                <p class="text-lg text-white font-semibold">${{ number_format($booking->total_amount, 2) }}</p>
                <p class="mt-2 text-sm text-slate-400">Phương thức: Trực tuyến</p>
            </div>
        </div>

        <div class="space-y-4">
            <div class="rounded-3xl bg-white/5 border border-white/10 p-6">
                <p class="text-slate-300">Nhấn nút bên dưới để hoàn tất thanh toán giả lập. Sau khi hoàn tất, bạn sẽ được chuyển hướng trở lại trang chi tiết đặt vé.</p>
            </div>
            <form method="POST" action="{{ route('payments.complete', $booking) }}">
                @csrf
                <input type="hidden" name="payment_method" value="online">
                <button type="submit" class="btn-primary w-full !py-4 rounded-3xl font-bold">Hoàn tất thanh toán</button>
            </form>
            <a href="{{ route('bookings.show', $booking) }}" class="inline-flex items-center justify-center w-full rounded-3xl border border-white/10 bg-slate-900/80 px-4 py-4 text-sm font-semibold text-slate-200 hover:bg-slate-900">Quay lại chi tiết đặt vé</a>
        </div>
    </div>
</div>
@endsection
