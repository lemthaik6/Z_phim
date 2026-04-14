@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
    <!-- Header -->
    <div class="mb-12">
        <div class="flex items-center gap-3 mb-4">
            <a href="{{ route('bookings.index') }}" class="text-indigo-400 hover:text-indigo-300 transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                <span>Quay lại</span>
            </a>
        </div>
        <h1 class="text-4xl md:text-5xl font-extrabold text-white tracking-tight">Chi tiết đặt vé</h1>
        <p class="mt-2 text-slate-400">#{{ str_pad($booking->id, 8, '0', STR_PAD_LEFT) }}</p>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-3xl border border-emerald-500/20 bg-emerald-500/10 p-4 text-emerald-200 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 rounded-3xl border border-rose-500/20 bg-rose-500/10 p-4 text-rose-200 text-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Movie & Showtime Info -->
            <div class="glass-card rounded-[2.5rem] p-8 border border-white/10">
                <h2 class="text-2xl font-bold text-white mb-6">Thông tin phim & suất chiếu</h2>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                    <!-- Movie Info -->
                    <div class="space-y-4">
                        @if($booking->showtime && $booking->showtime->movie)
                            <div>
                                <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest mb-2">Tiêu đề phim</p>
                                <p class="text-2xl font-bold text-white">{{ $booking->showtime->movie->title }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest mb-2">Thời lượng</p>
                                <p class="text-lg text-slate-300">{{ $booking->showtime->movie->duration }} phút</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest mb-2">Thể loại</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($booking->showtime->movie->genres as $genre)
                                        <span class="inline-flex items-center rounded-full bg-white/5 px-3 py-1 text-xs font-medium text-slate-300 border border-white/10">{{ $genre->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Showtime Info -->
                    <div class="space-y-4 pt-4 sm:pt-0 sm:border-l border-white/10 sm:pl-8">
                        @if($booking->showtime)
                            <div>
                                <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest mb-2">Ngày & Giờ</p>
                                <p class="text-lg font-bold text-white">
                                    {{ \Carbon\Carbon::parse($booking->showtime->start_time)->format('l, d M Y') }}
                                </p>
                                <p class="text-sm text-indigo-300 mt-1">
                                    {{ \Carbon\Carbon::parse($booking->showtime->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->showtime->end_time)->format('H:i') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest mb-2">Rạp & Phòng</p>
                                <p class="text-lg font-bold text-white">{{ $booking->showtime->room?->cinema?->name }}</p>
                                <p class="text-sm text-slate-400">Phòng {{ $booking->showtime->room?->name }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Seats Info -->
            <div class="glass-card rounded-[2.5rem] p-8 border border-white/10">
                <h2 class="text-2xl font-bold text-white mb-6">Ghế được đặt</h2>
                
                <div class="flex flex-wrap gap-3">
                    @if($booking->bookingDetails && $booking->bookingDetails->count() > 0)
                        @foreach($booking->bookingDetails as $detail)
                            <div class="inline-flex items-center rounded-2xl bg-gradient-to-r from-indigo-500/20 to-purple-500/20 px-4 py-3 border border-indigo-500/30">
                                <svg class="w-5 h-5 text-indigo-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                </svg>
                                <div>
                                    <p class="text-[10px] font-bold text-indigo-300 uppercase tracking-wider">Hàng {{ $detail->seat->row_number }}</p>
                                    <p class="text-white font-bold">Ghế {{ $detail->seat->column_number }}</p>
                                    @if($detail->seat->type === 'vip')
                                        <p class="text-[10px] text-amber-300 font-semibold uppercase">VIP</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-slate-400">Không có thông tin ghế</p>
                    @endif
                </div>
            </div>

            <!-- Status Timeline -->
            <div class="glass-card rounded-[2.5rem] p-8 border border-white/10">
                <h2 class="text-2xl font-bold text-white mb-6">Trạng thái đặt vé</h2>
                
                <div class="space-y-4">
                    <!-- Pending -->
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="w-12 h-12 rounded-full {{ $booking->status !== 'cancelled' ? 'bg-indigo-500/20 border border-indigo-500' : 'bg-slate-800 border border-slate-700' }} flex items-center justify-center">
                                <svg class="w-6 h-6 {{ $booking->status !== 'cancelled' ? 'text-indigo-400' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            @if($booking->status !== 'pending' && $booking->status !== 'cancelled')
                                <div class="w-1 h-8 bg-gradient-to-b from-indigo-500 to-indigo-500/30 my-1"></div>
                            @else
                                <div class="w-1 h-8 bg-slate-700 my-1"></div>
                            @endif
                        </div>
                        <div class="pt-2">
                            <p class="text-sm font-bold text-white">Đặt vé khởi tạo</p>
                            <p class="text-xs text-slate-400">{{ $booking->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>

                    <!-- Payment -->
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="w-12 h-12 rounded-full {{ in_array($booking->status, ['paid', 'confirmed']) ? 'bg-emerald-500/20 border border-emerald-500' : ($booking->status === 'cancelled' ? 'bg-rose-500/20 border border-rose-500' : 'bg-slate-800 border border-slate-700') }} flex items-center justify-center">
                                @if(in_array($booking->status, ['paid', 'confirmed']))
                                    <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                @elseif($booking->status === 'cancelled')
                                    <svg class="w-6 h-6 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                @else
                                    <div class="w-2 h-2 rounded-full bg-slate-500"></div>
                                @endif
                            </div>
                            @if($booking->status === 'cancelled')
                                <!-- No line -->
                            @endif
                        </div>
                        <div class="pt-2">
                            <p class="text-sm font-bold text-white">Thanh toán</p>
                            @if(in_array($booking->status, ['paid', 'confirmed']))
                                <p class="text-xs text-emerald-400">{{ $booking->paid_at ? $booking->paid_at->format('d M Y H:i') : 'Đã xác nhận' }}</p>
                            @elseif($booking->status === 'cancelled')
                                <p class="text-xs text-rose-400">Đã hủy</p>
                            @else
                                <p class="text-xs text-amber-400">Chờ thanh toán</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Summary Card -->
            <div class="glass-card rounded-[2.5rem] p-8 border border-white/10 sticky top-32 space-y-6">
                <!-- Status Badge -->
                <div class="p-4 rounded-2xl {{ $booking->status === 'paid' ? 'bg-emerald-500/10 border border-emerald-500/30' : ($booking->status === 'pending' ? 'bg-amber-500/10 border border-amber-500/30' : 'bg-rose-500/10 border border-rose-500/30') }}">
                    <p class="text-[10px] font-bold uppercase tracking-widest {{ $booking->status === 'paid' ? 'text-emerald-300' : ($booking->status === 'pending' ? 'text-amber-300' : 'text-rose-300') }}">
                        Trạng thái
                    </p>
                    <p class="text-xl font-bold {{ $booking->status === 'paid' ? 'text-emerald-200' : ($booking->status === 'pending' ? 'text-amber-200' : 'text-rose-200') }} mt-2">
                        {{ $booking->status === 'paid' ? 'Đã thanh toán' : ($booking->status === 'pending' ? 'Đang chờ' : 'Đã hủy') }}
                    </p>
                </div>

                <!-- Price Summary -->
                <div class="border-t border-white/10 pt-6">
                    <div class="space-y-3 mb-6">
                        @if($booking->bookingDetails && $booking->bookingDetails->count() > 0)
                            @php $totalSeats = $booking->bookingDetails->count(); @endphp
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-400">{{ $totalSeats }} ghế × ${{ number_format($booking->total_amount / $totalSeats, 2) }}</span>
                                <span class="text-white font-semibold">${{ number_format($booking->total_amount, 2) }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="flex justify-between items-end">
                        <span class="text-slate-500 font-medium uppercase tracking-widest text-xs">Tổng tiền</span>
                        <div class="text-right">
                            <span class="text-4xl font-black text-white">${{ number_format($booking->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Methods (if pending) -->
                @if($booking->status === 'pending' && (!$booking->locked_until || \Carbon\Carbon::parse($booking->locked_until) > now()))
                <div class="border-t border-white/10 pt-6">
                    <p class="text-sm text-slate-400 mb-4 font-semibold uppercase tracking-widest">Phương thức thanh toán</p>
                    <div class="space-y-2 mb-6">
                        <label class="cursor-pointer flex items-center rounded-2xl border border-indigo-500/30 bg-indigo-500/10 p-3 transition hover:bg-indigo-500/20">
                            <input type="radio" name="payment_method" value="online" checked class="hidden" id="payment-online">
                            <div class="w-4 h-4 rounded border-2 border-indigo-500 flex items-center justify-center">
                                <div class="w-2 h-2 rounded-full bg-indigo-500"></div>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-bold text-white">Online</p>
                                <p class="text-xs text-slate-400">Thanh toán trực tuyến</p>
                            </div>
                        </label>
                        <label class="cursor-pointer flex items-center rounded-2xl border border-white/10 bg-white/5 p-3 transition hover:border-white/20">
                            <input type="radio" name="payment_method" value="card" class="hidden" id="payment-card">
                            <div class="w-4 h-4 rounded border-2 border-slate-600 flex items-center justify-center"></div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-bold text-white">Thẻ</p>
                                <p class="text-xs text-slate-400">Thanh toán bằng thẻ</p>
                            </div>
                        </label>
                        <label class="cursor-pointer flex items-center rounded-2xl border border-white/10 bg-white/5 p-3 transition hover:border-white/20">
                            <input type="radio" name="payment_method" value="cash" class="hidden" id="payment-cash">
                            <div class="w-4 h-4 rounded border-2 border-slate-600 flex items-center justify-center"></div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-bold text-white">Tiền mặt</p>
                                <p class="text-xs text-slate-400">Thanh toán tại quầy</p>
                            </div>
                        </label>
                    </div>

                    <div class="space-y-3">
                        <button onclick="proceedToPayment(event, {{ $booking->id }})" class="btn-primary w-full !py-4 rounded-2xl flex items-center justify-center font-bold">
                            <span>Xác nhận thanh toán</span>
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </button>
                        <button onclick="cancelBooking({{ $booking->id }})" class="w-full !py-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-300 font-bold hover:bg-rose-500/20 transition-colors">
                            Hủy đặt vé
                        </button>
                    </div>

                    <p class="text-center text-[10px] text-slate-500 mt-4 font-medium leading-relaxed">
                        ⏱️ Ghế của bạn sẽ được giữ cho đến <strong>{{ $booking->locked_until ? $booking->locked_until->format('H:i') : '5 phút tới' }}</strong>
                    </p>
                </div>
                @elseif($booking->status === 'pending')
                <div class="rounded-2xl bg-rose-500/10 border border-rose-500/30 p-4">
                    <p class="text-sm text-rose-300 font-semibold">⏰ Thời gian giữ ghế đã hết!</p>
                    <p class="text-xs text-rose-300/70 mt-2">Vui lòng đặt lại vé để tiếp tục.</p>
                    <a href="{{ route('movies.index') }}" class="inline-block mt-3 text-xs font-bold text-rose-300 hover:text-rose-200">← Quay lại chọn phim</a>
                </div>
                @elseif($booking->status === 'paid' && $booking->paid_at)
                <div class="rounded-2xl bg-emerald-500/10 border border-emerald-500/30 p-4">
                    <p class="text-sm text-emerald-300 font-semibold">✓ Thanh toán thành công!</p>
                    <p class="text-xs text-emerald-300/70 mt-2">Vé của bạn đã được xác nhận. Hãy đến rạp 15 phút trước giờ chiếu.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function proceedToPayment(event, bookingId) {
    const paymentMethod = document.querySelector('input[name="payment_method"]:checked')?.value || 'online';
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/payments/${bookingId}/complete`;
    form.innerHTML = `
        <input type="hidden" name="_token" value="${window.Laravel.csrfToken}">
        <input type="hidden" name="payment_method" value="${paymentMethod}">
    `;
    document.body.appendChild(form);
    form.submit();
}

function cancelBooking(bookingId) {
    if (!confirm('Bạn có chắc muốn hủy đặt vé này không? Bất kỳ khoản thanh toán đã hoàn tất sẽ được hoàn lại.')) {
        return;
    }

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/bookings/${bookingId}/cancel`;
    form.innerHTML = `<input type="hidden" name="_token" value="${window.Laravel.csrfToken}">`;
    document.body.appendChild(form);
    form.submit();
}

// Handle radio button selection styling
document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.querySelectorAll('label').forEach(label => {
            if (label.querySelector('input[name="payment_method"]')) {
                if (label.querySelector('input[name="payment_method"]').checked) {
                    label.classList.remove('border-white/10', 'bg-white/5');
                    label.classList.add('border-indigo-500/30', 'bg-indigo-500/10');
                } else {
                    label.classList.remove('border-indigo-500/30', 'bg-indigo-500/10');
                    label.classList.add('border-white/10', 'bg-white/5');
                }
            }
        });
    });
});
</script>
@endsection
