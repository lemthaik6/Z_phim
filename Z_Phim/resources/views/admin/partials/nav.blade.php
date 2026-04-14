<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
    <div class="glass-card rounded-[2rem] p-6 border border-white/10 bg-slate-950/80 shadow-xl">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-indigo-300">Khu vực quản trị</p>
                <h2 class="mt-2 text-3xl font-extrabold text-white">Bảng điều khiển Admin</h2>
                <p class="mt-2 text-sm text-slate-400">Quản lý phim, lịch chiếu và đặt vé trực tiếp từ một nơi.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-3 rounded-2xl border border-white/10 text-sm font-semibold text-slate-200 bg-slate-950/70 hover:bg-slate-900 transition">
                    Dashboard
                </a>
                <a href="{{ route('admin.movies.index') }}" class="inline-flex items-center gap-2 px-4 py-3 rounded-2xl border border-white/10 text-sm font-semibold text-slate-200 bg-slate-950/70 hover:bg-slate-900 transition">
                    Phim
                </a>
                <a href="{{ route('admin.showtimes.index') }}" class="inline-flex items-center gap-2 px-4 py-3 rounded-2xl border border-white/10 text-sm font-semibold text-slate-200 bg-slate-950/70 hover:bg-slate-900 transition">
                    Suất chiếu
                </a>
                <a href="{{ route('admin.bookings.index') }}" class="inline-flex items-center gap-2 px-4 py-3 rounded-2xl border border-white/10 text-sm font-semibold text-slate-200 bg-slate-950/70 hover:bg-slate-900 transition">
                    Đặt chỗ
                </a>
                <a href="{{ route('admin.reviews.index') }}" class="inline-flex items-center gap-2 px-4 py-3 rounded-2xl border border-white/10 text-sm font-semibold text-slate-200 bg-slate-950/70 hover:bg-slate-900 transition">
                    Reviews
                </a>
                <a href="{{ route('admin.payments.index') }}" class="inline-flex items-center gap-2 px-4 py-3 rounded-2xl border border-white/10 text-sm font-semibold text-slate-200 bg-slate-950/70 hover:bg-slate-900 transition">
                    Payments
                </a>
            </div>
        </div>
    </div>
</div>
