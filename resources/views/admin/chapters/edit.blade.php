@extends('admin.layout')

@section('title', 'Edit Chapter')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Edit Chapter</h1>
    <p class="text-gray-600">Update: {{ $chapter->manga->title }} - Chapter {{ $chapter->chapter_number }}</p>
</div>

<div class="admin-card max-w-6xl">
    <form action="{{ route('admin.chapters.update', $chapter) }}" method="POST" id="chapter-form">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="manga_id" class="form-label">Manga *</label>
            <select id="manga_id"
                    name="manga_id"
                    class="form-select choices-single @error('manga_id') border-red-500 @enderror"
                    required>
                <option value="">Select Manga</option>
                @foreach($mangas as $manga)
                    <option value="{{ $manga->id }}" {{ old('manga_id', $chapter->manga_id) == $manga->id ? 'selected' : '' }}>
                        {{ $manga->title }}
                    </option>
                @endforeach
            </select>
            @error('manga_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="form-group">
                <label for="chapter_number" class="form-label">Chapter Number *</label>
                <input type="number"
                       id="chapter_number"
                       name="chapter_number"
                       step="0.01"
                       class="form-input @error('chapter_number') border-red-500 @enderror"
                       value="{{ old('chapter_number', $chapter->chapter_number) }}"
                       required>
                <p class="text-sm text-gray-500 mt-1">Can be decimal (e.g., 12.5)</p>
                @error('chapter_number')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="title" class="form-label">Chapter Title</label>
                <input type="text"
                       id="title"
                       name="title"
                       class="form-input @error('title') border-red-500 @enderror"
                       value="{{ old('title', $chapter->title) }}"
                       placeholder="Optional">
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Chapter Images Management -->
        <div class="form-group">
            <label class="form-label">Chapter Images ({{ $chapter->images->count() }})</label>

            @if($chapter->images->count() > 0)
            <div id="images-grid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
                @foreach($chapter->images->sortBy('order') as $image)
                <div class="image-item group relative bg-white rounded-lg shadow-md overflow-hidden transition-all duration-200 hover:shadow-xl"
                     data-id="{{ $image->id }}">
                    <!-- Drag Handle -->
                    <div class="drag-handle absolute top-2 left-2 cursor-move z-10 bg-gray-800 bg-opacity-75 text-white p-1.5 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M7 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 2zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 14zm6-8a2 2 0 1 0-.001-4.001A2 2 0 0 0 13 6zm0 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 14z"></path>
                        </svg>
                    </div>

                    <!-- Delete Button -->
                    <button type="button"
                            class="delete-image absolute top-2 right-2 z-10 bg-red-500 hover:bg-red-600 text-white p-1.5 rounded-full opacity-0 group-hover:opacity-100 transition-all duration-200 transform hover:scale-110"
                            data-id="{{ $image->id }}"
                            title="Delete image">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>

                    <!-- Image -->
                    <img src="{{ asset('storage/' . $image->image_path) }}"
                         alt="Page {{ $image->order }}"
                         class="w-full h-40 object-cover">

                    <!-- Order Badge -->
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-3">
                        <div class="flex items-center justify-between">
                            <span class="image-order text-white text-sm font-semibold">Page {{ $image->order }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-sm text-blue-700">
                        <strong>Tip:</strong> Drag images to reorder them. Click the trash icon to delete. Changes are saved instantly.
                    </p>
                </div>
            </div>
            @else
            <div class="text-center py-8 bg-gray-50 rounded-lg mb-6">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <p class="mt-2 text-sm text-gray-500">No images yet. Add some below!</p>
            </div>
            @endif

            <!-- Modern Upload Zone -->
            <div class="upload-zone">
                <div id="drop-zone" class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center transition-all duration-200 hover:border-orange-500 hover:bg-orange-50">
                    <input type="file"
                           id="image-upload"
                           multiple
                           accept="image/*"
                           class="hidden">

                    <div class="upload-content">
                        <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        <p class="mt-4 text-lg font-medium text-gray-700">Drag & Drop Images Here</p>
                        <p class="mt-2 text-sm text-gray-500">or click to browse</p>
                        <p class="mt-4 text-xs text-gray-400">JPG, PNG, GIF, WebP - Max 20MB each</p>
                    </div>
                </div>

                <!-- Preview Area -->
                <div id="preview-grid" class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4 hidden"></div>

                <!-- Upload Button -->
                <button type="button"
                        id="upload-btn"
                        class="hidden mt-4 w-full btn-admin-primary">
                    <svg class="inline-block w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                    Upload Images
                </button>
            </div>
        </div>

        <div class="flex space-x-4 mt-8">
            <button type="submit" class="btn-admin-primary">
                Update Chapter
            </button>
            <a href="{{ route('admin.chapters.index') }}" class="btn-admin-secondary">
                Cancel
            </a>
        </div>
    </form>
</div>

<!-- Toast Notification -->
<div id="toast" class="fixed top-4 right-4 z-50 hidden">
    <div class="bg-white rounded-lg shadow-lg p-4 max-w-sm border-l-4 border-green-500">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <p id="toast-message" class="text-gray-800 font-medium"></p>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loading-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
    <div class="bg-white rounded-lg p-6">
        <div class="flex items-center space-x-4">
            <svg class="animate-spin h-8 w-8 text-orange-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="text-gray-700 font-medium">Processing...</p>
        </div>
    </div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
    window.chapterId = {{ $chapter->id }};
</script>
@endsection
