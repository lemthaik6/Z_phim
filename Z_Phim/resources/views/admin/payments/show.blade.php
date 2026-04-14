@extends('layouts.app')

@section('content')
@include('admin.partials.nav')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white">Chi tiết thanh toán</h1>
            <p class="mt-2 text-slate-400">Thông tin chi tiết của giao dịch #{{ $payment->id }}</p>
    <div class="glass-card rounded-[2.5rem] border border-white/10 bg-slate-950/70 p-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="space-y-4">
                <div>
                    <h2 class="text-sm uppercase tracking-widest text-slate-500">Booking</h2>
                    <p class="text-lg font-semibold text-white">#{{ $payment->booking->id ?? 'N/A' }}</p>
                </div>
                <div>
                    <h2 class="text-sm uppercase tracking-widest text-slate-500">Người đặt</h2>
                    <p class="text-lg font-semibold text-white">{{ $payment->booking->user->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <h2 class="text-sm uppercase tracking-widest text-slate-500">Phim</h2>
                    <p class="text-lg font-semibold text-white">{{ $payment->booking->showtime->movie->title ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="space-y-4">
                <div>
                    <h2 class="text-sm uppercase tracking-widest text-slate-500">Số tiền</h2>
                    <p class="text-lg font-semibold text-white">${{ number_format($payment->amount, 2) }}</p>
                </div>
                <div>
                    <h2 class="text-sm uppercase tracking-widest text-slate-500">Phương thức</h2>
                    <p class="text-lg font-semibold text-white">{{ ucfirst($payment->payment_method) }}</p>
                </div>
                <div>
                    <h2 class="text-sm uppercase tracking-widest text-slate-500">Trạng thái</h2>
                    <p class="text-lg font-semibold text-white">{{ ucfirst($payment->status) }}</p>
                </div>
            </div>
        </div>

        <div class="mt-10">
            <a href="{{ route('admin.payments.index') }}" class="inline-flex items-center px-5 py-3 rounded-3xl bg-slate-900/80 border border-white/10 text-sm font-semibold text-white hover:bg-slate-800 transition">Quay lại danh sách thanh toán</a>
        </div>
    </div>
</div>
@endsection
