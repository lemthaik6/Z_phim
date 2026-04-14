@extends('layouts.app')

@section('content')
@include('admin.partials.nav')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8 flex items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-white">Quản lý thanh toán</h1>
            <p class="mt-2 text-slate-400">Xem toàn bộ giao dịch và cập nhật trạng thái thanh toán.</p>
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
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Booking</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Người đặt</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Số tiền</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Phương thức</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Trạng thái</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Hành động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800 bg-slate-950/80">
                    @forelse($payments as $payment)
                    <tr>
                        <td class="px-6 py-4 text-sm text-white">{{ $payment->id }}</td>
                        <td class="px-6 py-4 text-sm text-slate-300">#{{ $payment->booking->id ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-slate-300">{{ $payment->booking->user->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-white">${{ number_format($payment->amount, 2) }}</td>
                        <td class="px-6 py-4 text-sm text-slate-300">{{ ucfirst($payment->payment_method) }}</td>
                        <td class="px-6 py-4 text-sm">
                            <form method="POST" action="{{ route('admin.payments.update', $payment) }}" class="flex items-center gap-2">
                                @csrf
                                @method('PUT')
                                <select name="status" class="rounded-2xl border border-white/10 bg-slate-900 text-slate-200 px-3 py-2">
                                    @foreach(['pending', 'completed', 'failed'] as $status)
                                        <option value="{{ $status }}" {{ $payment->status === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn-primary !py-2 px-4 text-sm">Cập nhật</button>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-sm space-x-2">
                            <a href="{{ route('admin.payments.show', $payment) }}" class="text-sky-400 hover:text-white">Xem</a>
                            <form method="POST" action="{{ route('admin.payments.destroy', $payment) }}" onsubmit="return confirm('Xoá giao dịch này?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-400 hover:text-white">Xóa</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-slate-500">Không có giao dịch nào.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-white/10 bg-slate-950/80">
            {{ $payments->links() }}
        </div>
    </div>
</div>
@endsection
