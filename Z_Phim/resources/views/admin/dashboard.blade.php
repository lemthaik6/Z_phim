@extends('layouts.app')

@section('content')
@include('admin.partials.nav')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-4xl font-black text-white tracking-tight mb-2">Trung tâm quản trị</h1>
            <p class="text-slate-400 font-medium">Giám sát hoạt động rạp phim của bạn</p>
        </div>
        
        <div class="flex items-center space-x-4">
            <div class="px-4 py-2 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 flex items-center space-x-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-xs font-bold text-emerald-400 uppercase tracking-widest">Hệ thống trực tuyến</span>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        <div class="glass-card rounded-[2rem] p-8 group hover:bg-slate-900/40 transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-indigo-500/10 rounded-2xl flex items-center justify-center text-indigo-400 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m-9 0h10m-9 0V1m10 3V1m0 3l1 1v16a2 2 0 01-2 2H6a2 2 0 01-2-2V5l1-1z"></path>
                    </svg>
                </div>
                <span class="text-[10px] font-black text-slate-600 uppercase tracking-widest">Thư viện</span>
            </div>
            <p class="text-sm font-bold text-slate-500 mb-1">Tổng phim</p>
            <p class="text-3xl font-black text-white">{{ $stats['movies'] }}</p>
        </div>

        <div class="glass-card rounded-[2rem] p-8 group hover:bg-slate-900/40 transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-500/10 rounded-2xl flex items-center justify-center text-purple-400 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <span class="text-[10px] font-black text-slate-600 uppercase tracking-widest">Lịch chiếu</span>
            </div>
            <p class="text-sm font-bold text-slate-500 mb-1">Suất chiếu hoạt động</p>
            <p class="text-3xl font-black text-white">{{ $stats['showtimes'] }}</p>
        </div>

        <div class="glass-card rounded-[2rem] p-8 group hover:bg-slate-900/40 transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-pink-500/10 rounded-2xl flex items-center justify-center text-pink-400 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <span class="text-[10px] font-black text-slate-600 uppercase tracking-widest">Đặt chỗ</span>
            </div>
            <p class="text-sm font-bold text-slate-500 mb-1">Tổng đặt chỗ</p>
            <p class="text-3xl font-black text-white">{{ $stats['bookings'] }}</p>
        </div>

        <div class="glass-card rounded-[2rem] p-8 group hover:bg-slate-900/40 transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-emerald-500/10 rounded-2xl flex items-center justify-center text-emerald-400 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <span class="text-[10px] font-black text-slate-600 uppercase tracking-widest">Doanh thu</span>
            </div>
            <p class="text-sm font-bold text-slate-500 mb-1">Tổng doanh thu</p>
            <p class="text-3xl font-black text-white">${{ number_format($stats['total_revenue'], 2) }}</p>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid gap-8 mb-12 lg:grid-cols-[2fr_1fr]">
        <!-- Revenue Chart -->
        <div class="glass-card rounded-[2rem] p-8">
            <div class="mb-8">
                <h2 class="text-2xl font-black text-white mb-1">Xu hướng doanh thu</h2>
                <p class="text-sm text-slate-400">6 tháng gần nhất</p>
            </div>
            <div class="relative h-80">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- Status Chart -->
        <div class="glass-card rounded-[2rem] p-8">
            <div class="mb-8">
                <h2 class="text-2xl font-black text-white mb-1">Trạng thái đặt chỗ</h2>
                <p class="text-sm text-slate-400">Phân bổ theo trạng thái</p>
            </div>
            <div class="relative h-80">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="glass-card rounded-[2.5rem] p-10 mb-12 relative overflow-hidden">
        <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-indigo-500/5 rounded-full blur-[100px] -z-10"></div>
        <h2 class="text-2xl font-black text-white mb-8 tracking-tight">Triển khai nhanh</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <a href="{{ route('admin.movies.create') }}" class="btn-primary !py-5 !rounded-[1.5rem] flex items-center justify-center group overflow-hidden relative">
                <span class="relative z-10 flex items-center">
                    <svg class="w-5 h-5 mr-3 group-hover:rotate-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Thêm phim mới
                </span>
                <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform"></div>
            </a>
            
            <a href="{{ route('admin.showtimes.create') }}" class="btn-secondary !py-5 !rounded-[1.5rem] flex items-center justify-center group">
                <svg class="w-5 h-5 mr-3 text-indigo-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Thêm suất chiếu
            </a>

            <a href="{{ route('admin.bookings.index') }}" class="btn-secondary !py-5 !rounded-[1.5rem] flex items-center justify-center group">
                <svg class="w-5 h-5 mr-3 text-purple-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                Xem đặt chỗ
            </a>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 pb-12">
        <!-- Phim mới -->
        <div class="glass-card rounded-[2.5rem] p-10">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-xl font-bold text-white tracking-tight">Phim mới</h3>
                <a href="{{ route('admin.movies.index') }}" class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest hover:text-white transition-colors">Xem tất cả</a>
            </div>
            <div class="space-y-4">
                @forelse($recentMovies as $movie)
                    <div class="flex items-center gap-4 p-4 rounded-2xl hover:bg-white/5 transition-colors">
                        <div class="w-12 h-12 rounded-xl bg-indigo-500/20 flex items-center justify-center text-indigo-400 flex-shrink-0">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 6a2 2 0 012-2h12a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM4 9a1 1 0 011-1h6a1 1 0 011 1v2H4V9z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-white truncate">{{ $movie->title }}</p>
                            <p class="text-xs text-slate-500">{{ $movie->duration }} phút · {{ $movie->created_at ? $movie->created_at->format('d M Y') : 'N/A' }}</p>
                        </div>
                        <a href="{{ route('admin.movies.edit', $movie) }}" class="text-indigo-400 hover:text-indigo-300 text-xs font-bold">Sửa</a>
                    </div>
                @empty
                    <p class="text-sm text-slate-500 py-4">Chưa có phim nào.</p>
                @endforelse
            </div>
        </div>

        <!-- Đặt chỗ gần đây -->
        <div class="glass-card rounded-[2.5rem] p-10">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-xl font-bold text-white tracking-tight">Giao dịch gần đây</h3>
                <a href="{{ route('admin.bookings.index') }}" class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest hover:text-white transition-colors">Nhật ký</a>
            </div>
            <div class="space-y-4">
                @forelse($recentBookings as $booking)
                    <div class="flex items-center justify-between p-4 rounded-2xl hover:bg-white/5 transition-colors">
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                            <div class="w-10 h-10 rounded-full bg-slate-800 border border-white/5 flex items-center justify-center text-xs font-bold text-slate-300 flex-shrink-0">
                                {{ strtoupper(substr($booking->user->name ?? 'U', 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-white truncate">{{ $booking->showtime?->movie?->title ?? 'Phim' }}</p>
                                <p class="text-xs text-slate-500">{{ $booking->user?->name ?? 'Người dùng' }}</p>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0 ml-4">
                            <p class="text-sm font-bold text-white">${{ number_format($booking->total_amount, 2) }}</p>
                            <span class="text-xs font-bold uppercase {{ in_array($booking->status, ['confirmed', 'paid']) ? 'text-emerald-400' : ($booking->status === 'pending' ? 'text-amber-400' : 'text-rose-400') }}">{{ ucfirst($booking->status) }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-500 py-4">Chưa có giao dịch.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const chartConfig = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                labels: {
                    color: '#cbd5e1',
                    font: { size: 12, weight: '600' },
                    padding: 20,
                    usePointStyle: true
                }
            }
        }
    };

    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart');
    if (revenueCtx) {
        new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: @json(array_column($revenueTrend, 'label')),
                datasets: [{
                    label: 'Doanh thu ($)',
                    data: @json(array_column($revenueTrend, 'value')),
                    backgroundColor: 'rgba(99, 102, 241, 0.8)',
                    borderColor: 'rgb(99, 102, 241)',
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false,
                    barThickness: 'flex',
                    maxBarThickness: 50,
                }]
            },
            options: {
                ...chartConfig,
                indexAxis: 'x',
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(148, 163, 184, 0.1)',
                            drawBorder: false
                        },
                        ticks: {
                            color: '#94a3b8',
                            font: { size: 11 }
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            color: '#94a3b8',
                            font: { size: 11 }
                        }
                    }
                }
            }
        });
    }

    // Status Chart
    const statusCtx = document.getElementById('statusChart');
    if (statusCtx) {
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Đã thanh toán', 'Đã xác nhận', 'Đang chờ', 'Đã hủy'],
                datasets: [{
                    data: [
                        @php echo ($statusCounts['paid'] ?? 0); @endphp,
                        @php echo ($statusCounts['confirmed'] ?? 0); @endphp,
                        @php echo ($statusCounts['pending'] ?? 0); @endphp,
                        @php echo ($statusCounts['cancelled'] ?? 0); @endphp
                    ],
                    backgroundColor: [
                        'rgba(99, 102, 241, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(239, 68, 68, 0.8)'
                    ],
                    borderColor: [
                        'rgb(99, 102, 241)',
                        'rgb(16, 185, 129)',
                        'rgb(245, 158, 11)',
                        'rgb(239, 68, 68)'
                    ],
                    borderWidth: 2,
                    cutout: '70%'
                }]
            },
            options: {
                ...chartConfig,
                plugins: {
                    ...chartConfig.plugins,
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleColor: '#f1f5f9',
                        bodyColor: '#cbd5e1',
                        borderColor: 'rgba(148, 163, 184, 0.2)',
                        borderWidth: 1,
                        padding: 12,
                        displayColors: true
                    }
                }
            }
        });
    }
</script>
@endsection
