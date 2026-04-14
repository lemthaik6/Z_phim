@extends('layouts.app')

@section('content')
@include('admin.partials.nav')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8 flex items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-white">Quản lý đánh giá</h1>
            <p class="mt-2 text-slate-400">Duyệt, cập nhật trạng thái hoặc xóa các bình luận của người dùng.</p>
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
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Phim</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Người dùng</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Điểm</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Tình trạng</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-400">Hành động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800 bg-slate-950/80">
                    @forelse($reviews as $review)
                    <tr>
                        <td class="px-6 py-4 text-sm text-white">{{ $review->id }}</td>
                        <td class="px-6 py-4 text-sm text-slate-300">{{ $review->movie->title ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-slate-300">{{ $review->user->name ?? 'Khách' }}</td>
                        <td class="px-6 py-4 text-sm text-white">{{ $review->rating }}/5</td>
                        <td class="px-6 py-4 text-sm">
                            <form method="POST" action="{{ route('admin.reviews.update', $review) }}" class="flex items-center gap-2">
                                @csrf
                                @method('PUT')
                                <select name="status" class="rounded-2xl border border-white/10 bg-slate-900 text-slate-200 px-3 py-2">
                                    @foreach(['pending', 'approved', 'rejected'] as $status)
                                        <option value="{{ $status }}" {{ $review->status === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn-primary !py-2 px-4 text-sm">Lưu</button>
                            </form>
                        </td>
                        <td class="px-6 py-4 text-sm space-x-2">
                            <button type="button" onclick="document.getElementById('review-comment-{{ $review->id }}').classList.toggle('hidden')" class="text-sky-400 hover:text-white">Xem</button>
                            <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" onsubmit="return confirm('Xoá bình luận này?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-400 hover:text-white">Xóa</button>
                            </form>
                        </td>
                    </tr>
                    <tr id="review-comment-{{ $review->id }}" class="hidden bg-slate-900/70">
                        <td colspan="6" class="px-6 py-4 text-slate-300">
                            <p class="font-semibold text-white mb-2">Bình luận:</p>
                            <p>{{ $review->comment }}</p>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-500">Không có đánh giá nào.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-white/10 bg-slate-950/80">
            {{ $reviews->links() }}
        </div>
    </div>
</div>
@endsection
