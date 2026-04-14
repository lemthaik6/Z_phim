@extends('layouts.app')

@section('content')
@include('admin.partials.nav')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white">Cập nhật trạng thái đặt chỗ</h1>
        <p class="mt-2 text-slate-400">Điều chỉnh trạng thái hoàn tất, chờ hoặc hủy.</p>
    </div>

    <div class="glass-card rounded-[2.5rem] p-8 bg-slate-950/80 border border-white/10">
        <form method="POST" action="{{ route('admin.bookings.update', $booking) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label for="status" class="block text-sm font-medium text-slate-300 mb-2">Trạng thái *</label>
                    <select id="status" name="status" required class="w-full rounded-2xl border border-slate-700 bg-slate-900/70 px-4 py-3 text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="pending" {{ old('status', $booking->status) === 'pending' ? 'selected' : '' }}>Đang chờ</option>
                        <option value="confirmed" {{ old('status', $booking->status) === 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                        <option value="cancelled" {{ old('status', $booking->status) === 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                        <option value="refunded" {{ old('status', $booking->status) === 'refunded' ? 'selected' : '' }}>Hoàn tiền</option>
                    </select>
                    @error('status')<p class="mt-2 text-sm text-rose-400">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="rounded-3xl bg-slate-900/60 p-6 border border-white/10">
                        <p class="text-sm uppercase tracking-widest text-indigo-400 mb-2">Người dùng</p>
                        <p class="text-white font-semibold">{{ $booking->user->name }}</p>
                        <p class="text-slate-400 text-sm">{{ $booking->user->email }}</p>
                    </div>
                    <div class="rounded-3xl bg-slate-900/60 p-6 border border-white/10">
                        <p class="text-sm uppercase tracking-widest text-indigo-400 mb-2">Tổng tiền</p>
                        <p class="text-white font-semibold">${{ number_format($booking->total_amount, 2) }}</p>
                        <p class="text-slate-400 text-sm">{{ $booking->showtime->movie->title ?? 'Phim chưa xác định' }}</p>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('admin.bookings.index') }}" class="rounded-2xl border border-slate-700 px-5 py-3 text-sm font-semibold text-slate-200 hover:bg-slate-900 transition">Quay lại</a>
                    <button type="submit" class="rounded-2xl bg-indigo-500 px-6 py-3 text-sm font-semibold text-white hover:bg-indigo-400 transition">Lưu trạng thái</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
