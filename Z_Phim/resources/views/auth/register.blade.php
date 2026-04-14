@extends('layouts.app')

@section('content')
<div class="relative min-h-[85vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 overflow-hidden">
    <!-- Background Decor -->
    <div class="absolute inset-0 -z-10">
        <img src="/images/hero.png" alt="Hình nền đăng ký" class="w-full h-full object-cover opacity-20 blur-sm">
        <div class="absolute inset-0 bg-slate-950/80"></div>
    </div>

    <div class="max-w-md w-full animate-fade-in">
        <div class="text-center mb-10">
            <h2 class="text-4xl font-black text-white tracking-tight mb-3">Tham gia Z-PHIM</h2>
            <p class="text-slate-400 font-medium">
                Bạn đã có tài khoản? 
                <a href="{{ route('login') }}" class="text-indigo-400 hover:text-indigo-300 transition-colors underline decoration-2 underline-offset-4">Đăng nhập tại đây</a>
            </p>
        </div>

        <div class="glass-card rounded-[2.5rem] p-10 relative overflow-hidden">
            <div class="absolute top-0 left-0 -ml-16 -mt-16 w-32 h-32 bg-purple-500/10 rounded-full blur-3xl"></div>
            
            <form class="space-y-4 relative z-10" method="POST" action="{{ route('register') }}">
                @csrf
                <div>
                    <label for="name" class="block text-xs font-bold text-indigo-400 uppercase tracking-widest mb-2 ml-1">Họ và tên</label>
                    <input id="name" name="name" type="text" autocomplete="name" required
                           class="block w-full px-5 py-4 bg-slate-950/50 border border-white/5 rounded-2xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all @error('name') border-rose-500/50 @enderror"
                           placeholder="Nguyễn Văn A" value="{{ old('name') }}">
                    @error('name')
                        <p class="mt-2 text-xs text-rose-400 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold text-indigo-400 uppercase tracking-widest mb-2 ml-1">Địa chỉ Email</label>
                    <input id="email" name="email" type="email" autocomplete="email" required
                           class="block w-full px-5 py-4 bg-slate-950/50 border border-white/5 rounded-2xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all @error('email') border-rose-500/50 @enderror"
                           placeholder="email@vd.com" value="{{ old('email') }}">
                    @error('email')
                        <p class="mt-2 text-xs text-rose-400 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-indigo-400 uppercase tracking-widest mb-2 ml-1">Mật khẩu</label>
                    <input id="password" name="password" type="password" autocomplete="new-password" required
                           class="block w-full px-5 py-4 bg-slate-950/50 border border-white/5 rounded-2xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all @error('password') border-rose-500/50 @enderror"
                           placeholder="Tạo mật khẩu mạnh">
                    @error('password')
                        <p class="mt-2 text-xs text-rose-400 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pb-4">
                    <label for="password_confirmation" class="block text-xs font-bold text-indigo-400 uppercase tracking-widest mb-2 ml-1">Xác nhận mật khẩu</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
                           class="block w-full px-5 py-4 bg-slate-950/50 border border-white/5 rounded-2xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all"
                           placeholder="Lặp lại mật khẩu">
                </div>

                <div>
                    <button type="submit" id="register-btn" class="btn-primary w-full !py-4 !text-base !rounded-2xl group flex items-center justify-center space-x-2">
                        <span class="font-bold uppercase tracking-widest">Tạo tài khoản</span>
                        <svg class="h-5 w-5 text-indigo-300 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
        
        <p class="text-center mt-8 text-[10px] text-slate-600 font-medium uppercase tracking-widest">
            Bằng cách tạo tài khoản, bạn đồng ý với <a href="#" class="hover:text-slate-400 transition-colors">Chính sách bảo mật</a>
        </p>
    </div>
</div>

@endsection
