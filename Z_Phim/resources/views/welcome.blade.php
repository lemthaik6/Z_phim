@extends('layouts.app')

@section('content')
<div class="relative min-h-[90vh] flex items-center justify-center pt-8">
    <!-- Hero Background Image with Overlay -->
    <div class="absolute inset-0 -z-20 overflow-hidden">
        <img src="/images/hero.png" alt="Hình ảnh rạp chiếu phim" class="w-full h-full object-cover opacity-40 scale-105 animate-slow-zoom">
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/60 to-transparent"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-slate-950/40 via-transparent to-transparent"></div>
    </div>

    <div class="max-w-5xl mx-auto text-center px-4 relative">
        <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 mb-6 animate-fade-in-up">
            <span class="flex h-2 w-2 rounded-full bg-indigo-500"></span>
            <span class="text-xs font-semibold text-indigo-400 uppercase tracking-widest">Đang chiếu 4K</span>
        </div>
        
        <h1 class="text-6xl md:text-8xl font-extrabold text-white mb-6 tracking-tight animate-fade-in-up" style="animation-delay: 0.1s">
            Trải nghiệm điện ảnh <br>
            <span class="text-glow bg-clip-text text-transparent bg-gradient-to-r from-indigo-400 via-purple-400 to-pink-400">chưa từng có</span>
        </h1>
        
        <p class="text-xl text-slate-400 mb-10 max-w-2xl mx-auto leading-relaxed animate-fade-in-up" style="animation-delay: 0.2s">
            Đặt vé những bom tấn mới nhất chỉ trong vài giây. Chọn ghế mượt mà, thanh toán an toàn và trải nghiệm xem phim đỉnh cao.
        </p>

        <div class="flex flex-col sm:flex-row gap-6 justify-center animate-fade-in-up" style="animation-delay: 0.3s">
            <a href="{{ route('movies.index') }}" class="btn-primary !px-10 !py-4 text-lg flex items-center justify-center group">
                <span>Duyệt phim</span>
                <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </a>

            @guest
                <a href="{{ route('login') }}" class="btn-secondary !px-10 !py-4 text-lg flex items-center justify-center">
                    <span>Đăng nhập</span>
                </a>
            @else
                <a href="{{ route('bookings.index') }}" class="btn-secondary !px-10 !py-4 text-lg flex items-center justify-center">
                    <span>Đặt chỗ của tôi</span>
                </a>
            @endguest
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="max-w-7xl mx-auto px-4 py-24 relative">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-1/2 h-px bg-gradient-to-r from-transparent via-indigo-500/50 to-transparent"></div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="glass-card rounded-3xl p-8 hover:bg-slate-900/40 transition-all group">
            <div class="w-14 h-14 bg-indigo-500/20 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                <svg width="28" height="28" class="text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-white mb-3">Đặt vé tức thì</h3>
            <p class="text-slate-400 leading-relaxed">Chọn phim và suất chiếu. Hệ thống xử lý vé nhanh chóng trong tích tắc.</p>
        </div>

        <div class="glass-card rounded-3xl p-8 hover:bg-slate-900/40 transition-all group">
            <div class="w-14 h-14 bg-purple-500/20 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                <svg width="28" height="28" class="text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-white mb-3">Khóa ghế an toàn</h3>
            <p class="text-slate-400 leading-relaxed">Ghế bạn chọn được khóa an toàn trong 5 phút, đảm bảo không ai có thể lấy trước khi bạn thanh toán.</p>
        </div>

        <div class="glass-card rounded-3xl p-8 hover:bg-slate-900/40 transition-all group">
            <div class="w-14 h-14 bg-pink-500/20 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                <svg width="28" height="28" class="text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-white mb-3">Thanh toán toàn cầu</h3>
            <p class="text-slate-400 leading-relaxed">Thanh toán tự tin qua VNPay, MoMo hoặc thẻ tín dụng với cổng bảo mật cao.</p>
        </div>
    </div>
</div>

<style>
@keyframes slow-zoom {
    0% { transform: scale(1); }
    100% { transform: scale(1.1); }
}
.animate-slow-zoom {
    animation: slow-zoom 20s infinite alternate ease-in-out;
}
@keyframes fade-in-up {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in-up {
    animation: fade-in-up 0.8s forwards ease-out;
    opacity: 0;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const storedToken = localStorage.getItem('api_token');
    if (storedToken) {
        setTimeout(() => {
            window.location.href = '/movies';
        }, 3000);
    }
});
</script>
@endsection
