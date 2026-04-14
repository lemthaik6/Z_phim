@extends('layouts.app')

@section('content')
@include('admin.partials.nav')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white">Chỉnh sửa suất chiếu</h1>
        <p class="mt-2 text-slate-400">Cập nhật thông tin thời gian và phòng chiếu.</p>
    </div>

    <div class="glass-card rounded-[2.5rem] p-8 bg-slate-950/80 border border-white/10">
        <form method="POST" action="{{ route('admin.showtimes.update', $showtime) }}">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label for="movie_id" class="block text-sm font-medium text-slate-300 mb-2">Phim *</label>
                    <select id="movie_id" name="movie_id" required class="w-full rounded-2xl border border-slate-700 bg-slate-900/70 px-4 py-3 text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Chọn phim</option>
                        @foreach($movies as $movie)
                            <option value="{{ $movie->id }}" {{ old('movie_id', $showtime->movie_id) == $movie->id ? 'selected' : '' }}>{{ $movie->title }}</option>
                        @endforeach
                    </select>
                    @error('movie_id')<p class="mt-2 text-sm text-rose-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="cinema_id" class="block text-sm font-medium text-slate-300 mb-2">Rạp *</label>
                    <select id="cinema_id" name="cinema_id" required class="w-full rounded-2xl border border-slate-700 bg-slate-900/70 px-4 py-3 text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Chọn rạp</option>
                        @foreach($cinemas as $cinema)
                            <option value="{{ $cinema->id }}" data-rooms='@json($cinema->rooms)' {{ old('cinema_id', $showtime->room->cinema_id) == $cinema->id ? 'selected' : '' }}>{{ $cinema->name }}</option>
                        @endforeach
                    </select>
                    @error('room_id')<p class="mt-2 text-sm text-rose-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="room_id" class="block text-sm font-medium text-slate-300 mb-2">Phòng *</label>
                    <select id="room_id" name="room_id" required class="w-full rounded-2xl border border-slate-700 bg-slate-900/70 px-4 py-3 text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Chọn phòng</option>
                    </select>
                    @error('room_id')<p class="mt-2 text-sm text-rose-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="start_time" class="block text-sm font-medium text-slate-300 mb-2">Bắt đầu *</label>
                    <input type="datetime-local" id="start_time" name="start_time" value="{{ old('start_time', $showtime->start_time->format('Y-m-d\TH:i')) }}" required
                           class="w-full rounded-2xl border border-slate-700 bg-slate-900/70 px-4 py-3 text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @error('start_time')<p class="mt-2 text-sm text-rose-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="end_time" class="block text-sm font-medium text-slate-300 mb-2">Kết thúc *</label>
                    <input type="datetime-local" id="end_time" name="end_time" value="{{ old('end_time', $showtime->end_time->format('Y-m-d\TH:i')) }}" required
                           class="w-full rounded-2xl border border-slate-700 bg-slate-900/70 px-4 py-3 text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @error('end_time')<p class="mt-2 text-sm text-rose-400">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="price" class="block text-sm font-medium text-slate-300 mb-2">Giá mỗi ghế *</label>
                    <input type="number" step="0.01" id="price" name="price" value="{{ old('price', $showtime->price) }}" required
                           class="w-full rounded-2xl border border-slate-700 bg-slate-900/70 px-4 py-3 text-slate-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @error('price')<p class="mt-2 text-sm text-rose-400">{{ $message }}</p>@enderror
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('admin.showtimes.index') }}" class="rounded-2xl border border-slate-700 px-5 py-3 text-sm font-semibold text-slate-200 hover:bg-slate-900 transition">Huỷ</a>
                    <button type="submit" class="rounded-2xl bg-indigo-500 px-6 py-3 text-sm font-semibold text-white hover:bg-indigo-400 transition">Lưu thay đổi</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function updateRoomOptions() {
    const cinemaSelect = document.getElementById('cinema_id');
    const roomSelect = document.getElementById('room_id');
    roomSelect.innerHTML = '<option value="">Chọn phòng</option>';

    const selectedCinema = cinemaSelect.selectedOptions[0];
    if (!selectedCinema) return;

    const rooms = JSON.parse(selectedCinema.dataset.rooms || '[]');
    rooms.forEach(room => {
        const option = document.createElement('option');
        option.value = room.id;
        option.textContent = room.name;
        if (room.id == '{{ old('room_id', $showtime->room_id) }}') {
            option.selected = true;
        }
        roomSelect.appendChild(option);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('cinema_id').addEventListener('change', updateRoomOptions);
    updateRoomOptions();
});
</script>
@endsection
