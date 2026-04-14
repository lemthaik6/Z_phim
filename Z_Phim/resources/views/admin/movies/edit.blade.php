@extends('layouts.app')

@section('content')
@include('admin.partials.nav')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Chỉnh sửa phim</h1>
        <p class="mt-2 text-gray-600">Cập nhật thông tin phim</p>
    </div>

    <div class="bg-white shadow-md rounded-lg p-6">
        <form method="POST" action="{{ route('admin.movies.update', $movie) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Tiêu đề *</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $movie->title) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 @enderror">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">Thời lượng (phút) *</label>
                    <input type="number" name="duration" id="duration" value="{{ old('duration', $movie->duration) }}" min="1"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('duration') border-red-500 @enderror">
                    @error('duration')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="release_date" class="block text-sm font-medium text-gray-700 mb-2">Ngày phát hành *</label>
                    <input type="date" name="release_date" id="release_date" value="{{ old('release_date', $movie->release_date?->format('Y-m-d')) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('release_date') border-red-500 @enderror">
                    @error('release_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="poster" class="block text-sm font-medium text-gray-700 mb-2">Ảnh poster</label>
                    <input type="file" name="poster" id="poster" accept="image/*,.jfif,.bmp"
                           class="w-full text-sm text-gray-700 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('poster') border-red-500 @enderror">
                    @error('poster')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    @if($movie->poster_url)
                        <div class="mt-4">
                            <p class="text-sm text-gray-500 mb-2">Poster hiện tại:</p>
                            <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" class="h-40 rounded-xl object-cover border border-gray-200">
                        </div>
                    @endif
                </div>

                <div class="md:col-span-2">
                    <label for="trailer" class="block text-sm font-medium text-gray-700 mb-2">URL trailer</label>
                    <input type="url" name="trailer" id="trailer" value="{{ old('trailer', $movie->trailer) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('trailer') border-red-500 @enderror">
                    @error('trailer')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Mô tả *</label>
                    <textarea name="description" id="description" rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description', $movie->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Thể loại</label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                        @php
                            $genres = \App\Models\Genre::all();
                        @endphp
                        @foreach($genres as $genre)
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="genre_ids[]" value="{{ $genre->id }}"
                                   {{ in_array($genre->id, old('genre_ids', $movie->genres->pluck('id')->toArray())) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">{{ $genre->name }}</span>
                        </label>
                        @endforeach
                    </div>
                    @error('genre_ids')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end space-x-3">
                <a href="{{ route('admin.movies.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Huỷ
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cập nhật phim
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
