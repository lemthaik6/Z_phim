@extends('layouts.app')

@section('content')
<div class="relative min-h-[85vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 overflow-hidden">
    <!-- Background Decor -->
    <div class="absolute inset-0 -z-10">
        <img src="/images/hero.png" alt="Hình nền đăng nhập" class="w-full h-full object-cover opacity-20 blur-sm">
        <div class="absolute inset-0 bg-slate-950/80"></div>
    </div>

    <div class="max-w-md w-full animate-fade-in">
        <div class="text-center mb-10">
            <h2 class="text-4xl font-black text-white tracking-tight mb-3">Chào mừng trở lại</h2>
            <p class="text-slate-400 font-medium">
                Bạn mới đến Z-PHIM? 
                <a href="{{ route('register') }}" class="text-indigo-400 hover:text-indigo-300 transition-colors underline decoration-2 underline-offset-4">Tạo tài khoản</a>
            </p>
        </div>

        <div class="glass-card rounded-[2.5rem] p-10 relative overflow-hidden">
            <div class="absolute top-0 right-0 -mr-16 -mt-16 w-32 h-32 bg-indigo-500/10 rounded-full blur-3xl"></div>
            
            <form class="space-y-6 relative z-10" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="email" class="block text-xs font-bold text-indigo-400 uppercase tracking-widest mb-2 ml-1">Địa chỉ Email</label>
                        <input id="email" name="email" type="email" autocomplete="email" required
                               class="block w-full px-5 py-4 bg-slate-950/50 border border-white/5 rounded-2xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all @error('email') border-rose-500/50 @enderror"
                               placeholder="Nhập email của bạn" value="{{ old('email') }}">
                        @error('email')
                            <p class="mt-2 text-xs text-rose-400 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password" class="block text-xs font-bold text-indigo-400 uppercase tracking-widest mb-2 ml-1">Mật khẩu</label>
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                               class="block w-full px-5 py-4 bg-slate-950/50 border border-white/5 rounded-2xl text-white placeholder-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500/50 transition-all @error('password') border-rose-500/50 @enderror"
                               placeholder="Tối thiểu 8 ký tự">
                        @error('password')
                            <p class="mt-2 text-xs text-rose-400 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center justify-between px-1">
                    <div class="flex items-center">
                        <input id="remember-me" name="remember" type="checkbox"
                               class="h-4 w-4 bg-slate-900 border-white/10 rounded text-indigo-600 focus:ring-indigo-500/50 transition-all">
                        <label for="remember-me" class="ml-2 block text-xs font-medium text-slate-400">
                            Giữ tôi đăng nhập
                        </label>
                    </div>

                    <div class="text-xs">
                        <a href="#" class="font-medium text-slate-500 hover:text-indigo-400 transition-colors">Quên mật khẩu?</a>
                    </div>
                </div>

                <div>
                    <button type="submit" id="login-btn" class="btn-primary w-full !py-4 !text-base !rounded-2xl group flex items-center justify-center space-x-2">
                        <span class="font-bold uppercase tracking-widest">Đăng nhập</span>
                        <svg class="h-5 w-5 text-indigo-300 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
        
        <p class="text-center mt-8 text-[10px] text-slate-600 font-medium uppercase tracking-widest">
            Bằng cách đăng nhập, bạn đồng ý với <a href="#" class="hover:text-slate-400 transition-colors">Điều khoản dịch vụ</a>
        </p>
    </div>
</div>

@endsection
