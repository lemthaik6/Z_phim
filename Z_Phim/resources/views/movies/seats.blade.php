@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
    <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-4xl font-extrabold text-white tracking-tight mb-2">Chọn ghế của bạn</h1>
            <p class="text-indigo-400 font-medium">{{ $movie->title }}</p>
            <div class="flex items-center space-x-3 mt-2 text-sm text-slate-500">
                <span>{{ \Carbon\Carbon::parse($showtime->start_time)->format('l, M d') }}</span>
                <span class="w-1 h-1 rounded-full bg-slate-700"></span>
                <span>{{ \Carbon\Carbon::parse($showtime->start_time)->format('H:i') }}</span>
                <span class="w-1 h-1 rounded-full bg-slate-700"></span>
                <span>{{ $showtime->room ? $showtime->room->name : 'Phòng chính' }}</span>
            </div>
        </div>
        
        <!-- Chú giải -->
        <div class="flex flex-wrap gap-6 py-4 px-6 rounded-2xl bg-white/5 border border-white/5">
            <div class="flex items-center space-x-2">
                <div class="w-4 h-4 rounded bg-slate-800 border border-white/10"></div>
                <span class="text-xs font-medium text-slate-400 uppercase tracking-wider">Còn trống</span>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-4 h-4 rounded bg-indigo-500 shadow-[0_0_8px_rgba(99,102,241,0.6)]"></div>
                <span class="text-xs font-medium text-indigo-400 uppercase tracking-wider">Đã chọn</span>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-4 h-4 rounded bg-rose-500/30 border border-rose-500/50"></div>
                <span class="text-xs font-medium text-slate-500 uppercase tracking-wider">Đã đặt</span>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-4 h-4 rounded bg-amber-500/30 border border-amber-500/50"></div>
                <span class="text-xs font-medium text-slate-500 uppercase tracking-wider">Đang khóa</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 items-start">
        <!-- Theater Layout -->
        <div class="lg:col-span-2">
            <!-- Khu vực màn hình -->
            <div class="relative mb-24 flex flex-col items-center">
                <div class="w-full h-2 bg-gradient-to-r from-indigo-500/0 via-indigo-500/50 to-indigo-500/0 rounded-full blur-[2px]"></div>
                <div class="w-[80%] h-12 bg-gradient-to-b from-indigo-500/20 to-transparent rounded-[100%] absolute top-2 blur-xl"></div>
                <div class="mt-4 text-[10px] font-black text-slate-600 uppercase tracking-[1em] ml-[1em]">MÀN HÌNH</div>
            </div>

            <!-- Lưới ghế -->
            <div class="relative">
                <div id="seats-container" class="flex flex-col gap-4 items-center overflow-x-auto pb-12">
                    <!-- Ghế sẽ được tải tại đây -->
                </div>
                
                <div id="seats-loading" class="flex flex-col items-center justify-center py-20">
                    <div class="relative w-12 h-12">
                        <div class="absolute inset-0 border-3 border-indigo-500/20 rounded-full"></div>
                        <div class="absolute inset-0 border-3 border-indigo-500 border-t-transparent rounded-full animate-spin"></div>
                    </div>
                    <p class="mt-4 text-slate-500 font-medium">Đang kiểm tra tình trạng phòng...</p>
                </div>
            </div>
        </div>

        <!-- Thanh bên đặt vé -->
        <div class="sticky top-32">
            <div id="booking-summary" class="glass-card rounded-[2.5rem] p-8 hidden animate-fade-in">
                <h3 class="text-xl font-bold text-white mb-6">Thông tin đặt vé</h3>
                
                <div id="selected-seats" class="space-y-3 mb-8">
                    <!-- Thẻ ghế đã chọn sẽ hiển thị ở đây -->
                </div>

                <div class="pt-6 border-t border-white/5 mb-8">
                    <div class="flex justify-between items-end">
                            <span class="text-slate-500 font-medium uppercase tracking-widest text-xs">Tổng tiền</span>
                        <div class="text-right">
                            <span id="total-price" class="text-4xl font-black text-white">$0</span>
                            <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest mt-1">Sẵn sàng đặt vé</p>
                        </div>
                    </div>
                </div>

                <button id="book-button" class="btn-primary w-full !py-6 !text-lg !rounded-3xl flex items-center justify-center space-x-3 group relative overflow-hidden">
                    <span class="relative z-10 font-black uppercase tracking-widest">Xác nhận đặt vé</span>
                    <svg class="w-5 h-5 relative z-10 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                    <div class="absolute inset-0 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 translate-x-[-100%] group-hover:translate-x-0 transition-transform duration-500 opacity-20"></div>
                </button>
                
                <p class="text-center text-[10px] text-slate-500 mt-6 font-medium leading-relaxed">
                    Khi nhấn xác nhận, ghế của bạn sẽ được giữ trong 5 phút.<br>
                    Vui lòng hoàn tất thanh toán trong thời gian này.
                </p>
                <p class="text-center text-[10px] text-slate-400 mt-3">
                    Bạn có thể thêm combo bắp ngô, nước và phụ kiện rạp phim ở trang chi tiết đơn hàng sau khi đặt vé.
                </p>
            </div>
            
            <!-- Empty State for static view -->
            <div id="booking-empty" class="glass-card rounded-[2.5rem] p-12 text-center border-dashed border-white/5 opacity-50">
                <div class="w-16 h-16 bg-slate-800/50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5l5 5-5 5M4 4h16m-7 8h7m-7 4h7"></path>
                    </svg>
                </div>
            <span class="text-slate-400 font-medium">Vui lòng chọn ghế ưu thích để tiếp tục.</span>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in {
    animation: fade-in 0.4s ease-out forwards;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const seatsContainer = document.getElementById('seats-container');
    const loading = document.getElementById('seats-loading');
    const bookingSummary = document.getElementById('booking-summary');
    const bookingEmpty = document.getElementById('booking-empty');
    const selectedSeatsDiv = document.getElementById('selected-seats');
    const totalPriceSpan = document.getElementById('total-price');
    const bookButton = document.getElementById('book-button');

    const movieId = {{ $movie->id }};
    const showtimeId = {{ $showtime->id }};
    const price = {{ $showtime->price }};

    let seats = [];
    let selectedSeats = [];

    // Load seats
    fetch(`/api/showtimes/${showtimeId}/seats`, {
        credentials: 'same-origin',
        headers: {
            'X-CSRF-TOKEN': window.Laravel.csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        loading.style.display = 'none';
        const seatsData = Array.isArray(data) ? data : (data.data || []);

        if (seatsData && seatsData.length > 0) {
            seats = seatsData;

            // Group seats by row
            const seatsByRow = {};
            seats.forEach(seat => {
                if (!seatsByRow[seat.row_number]) {
                    seatsByRow[seat.row_number] = [];
                }
                seatsByRow[seat.row_number].push(seat);
            });

            // Iterate through rows and create HTML
            Object.keys(seatsByRow).sort((a, b) => parseInt(a) - parseInt(b)).forEach(rowNum => {
                const rowSeats = seatsByRow[rowNum];
                rowSeats.sort((a, b) => a.column_number - b.column_number);

                const rowWrapper = document.createElement('div');
                rowWrapper.className = 'flex items-center space-x-3';

                // Row Label Left
                const labelLeft = document.createElement('div');
                labelLeft.className = 'w-6 text-[10px] font-black text-slate-600';
                labelLeft.textContent = rowNum;
                rowWrapper.appendChild(labelLeft);

                const seatRow = document.createElement('div');
                seatRow.className = 'flex space-x-2';

                rowSeats.forEach(seat => {
                    const seatBtn = document.createElement('button');
                    seatBtn.dataset.seatId = seat.id;
                    seatBtn.dataset.row = seat.row_number;
                    seatBtn.dataset.col = seat.column_number;
                    
                    let baseClasses = 'w-8 h-8 rounded-lg flex items-center justify-center text-[10px] font-bold transition-all duration-300 ';
                    
                    if (seat.is_occupied) {
                        seatBtn.className = baseClasses + 'bg-rose-500/10 border border-rose-500/30 text-rose-500/40 cursor-not-allowed';
                        seatBtn.disabled = true;
                    } else if (seat.locked_until) {
                        seatBtn.className = baseClasses + 'bg-amber-500/10 border border-amber-500/30 text-amber-500/40 cursor-not-allowed';
                        seatBtn.disabled = true;
                    } else {
                        seatBtn.className = baseClasses + 'bg-slate-800 border border-white/5 text-slate-500 hover:bg-slate-700 hover:text-white hover:border-white/20 hover:scale-110';
                        seatBtn.addEventListener('click', () => toggleSeat(seat));
                    }

                    seatBtn.textContent = seat.column_number;
                    seatRow.appendChild(seatBtn);
                });

                rowWrapper.appendChild(seatRow);
                
                // Row Label Right
                const labelRight = document.createElement('div');
                labelRight.className = 'w-6 text-[10px] font-black text-slate-600 text-right';
                labelRight.textContent = rowNum;
                rowWrapper.appendChild(labelRight);

                seatsContainer.appendChild(rowWrapper);
            });
        }
    });

    function toggleSeat(seat) {
        const index = selectedSeats.findIndex(s => s.id === seat.id);
        if (index > -1) {
            selectedSeats.splice(index, 1);
        } else {
            selectedSeats.push(seat);
        }
        updateUI();
    }

    function updateUI() {
        document.querySelectorAll('[data-seat-id]').forEach(btn => {
            const seatId = parseInt(btn.dataset.seatId);
            const isSelected = selectedSeats.some(s => s.id === seatId);
            
            if (isSelected) {
                btn.className = 'w-8 h-8 rounded-lg flex items-center justify-center text-[10px] font-bold transition-all duration-300 bg-indigo-500 border border-indigo-400 text-white shadow-[0_0_12px_rgba(99,102,241,0.6)] scale-110 z-10';
            } else if (!btn.disabled) {
                btn.className = 'w-8 h-8 rounded-lg flex items-center justify-center text-[10px] font-bold transition-all duration-300 bg-slate-800 border border-white/5 text-slate-500 hover:bg-slate-700 hover:text-white hover:border-white/20 hover:scale-110';
            }
        });

        if (selectedSeats.length > 0) {
            bookingSummary.classList.remove('hidden');
            bookingEmpty.classList.add('hidden');
            
            selectedSeatsDiv.innerHTML = selectedSeats.map(seat => `
                <div class="flex items-center justify-between p-4 rounded-2xl bg-white/5 border border-white/5">
                    <div>
                        <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest">Hàng ${seat.row_number}</p>
                        <p class="text-white font-bold">Ghế ${seat.column_number}</p>
                    </div>
                    <p class="text-slate-400 font-medium text-sm">$${price}</p>
                </div>
            `).join('');

            totalPriceSpan.textContent = `$${selectedSeats.length * price}`;
        } else {
            bookingSummary.classList.add('hidden');
            bookingEmpty.classList.remove('hidden');
        }
    }

    bookButton.addEventListener('click', function() {
        if (selectedSeats.length === 0) return;
        
        bookButton.disabled = true;
        bookButton.innerHTML = `
            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="ml-3">Đang giữ ghế...</span>
        `;

        fetch('/api/bookings', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'X-CSRF-TOKEN': window.Laravel.csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                showtime_id: showtimeId,
                seat_ids: selectedSeats.map(s => s.id)
            })
        })
        .then(async response => {
            const data = await response.json().catch(() => ({}));

            if (!response.ok) {
                throw data;
            }

            return data;
        })
        .then(data => {
            if (data.booking_id) {
                window.location.href = `/bookings/${data.booking_id}`;
            } else {
                const message = data.message || 'Đặt vé không thành công. Vui lòng thử lại.';
                alert(message);
                window.location.reload();
            }
        })
        .catch(error => {
            const message = error?.message || 'Đặt vé không thành công. Vui lòng thử lại.';
            alert(message);
            bookButton.disabled = false;
            bookButton.innerHTML = '<span class="relative z-10 font-black uppercase tracking-widest">Xác nhận đặt vé</span><svg class="w-5 h-5 relative z-10 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg><div class="absolute inset-0 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 translate-x-[-100%] group-hover:translate-x-0 transition-transform duration-500 opacity-20"></div>';
        });
    });
});
</script>
@endsection
