@extends('admin.layout')

@section('title', 'Create Chapter')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Create Chapter</h1>
    <p class="text-gray-600">Add a new chapter</p>
</div>

<div class="admin-card max-w-6xl">
    <form action="{{ route('admin.chapters.store') }}" method="POST" enctype="multipart/form-data" id="chapter-create-form">
        @csrf

        <div class="form-group">
            <label for="manga_id" class="form-label">Manga *</label>
            <select id="manga_id"
                    name="manga_id"
                    class="form-select choices-single @error('manga_id') border-red-500 @enderror"
                    required>
                <option value="">Select Manga</option>
                @foreach($mangas as $manga)
                    <option value="{{ $manga->id }}" {{ old('manga_id') == $manga->id ? 'selected' : '' }}>
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
                       value="{{ old('chapter_number') }}"
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
                       value="{{ old('title') }}"
                       placeholder="Optional">
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Modern Upload Zone -->
        <div class="form-group">
            <label class="form-label">Chapter Images *</label>

            <div class="upload-zone-create">
                <div id="drop-zone-create" class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center transition-all duration-200 hover:border-orange-500 hover:bg-orange-50">
                    <input type="file"
                           id="images"
                           name="images[]"
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

                <!-- Preview Area with Sortable -->
                <div id="preview-grid-create" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mt-6 hidden"></div>

                @error('images')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                @error('images.*')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div id="images-info" class="bg-blue-50 border-l-4 border-blue-500 p-4 mt-6 hidden">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <p class="text-sm text-blue-700">
                            <strong>Tip:</strong> Drag images to reorder them before upload. Click the trash icon to remove unwanted images.
                        </p>
                        <p class="text-sm text-blue-600 mt-1">
                            <span id="image-count" class="font-semibold">0</span> image(s) selected
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex space-x-4 mt-8">
            <button type="submit" class="btn-admin-primary">
                Create Chapter
            </button>
            <a href="{{ route('admin.chapters.index') }}" class="btn-admin-secondary">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
    window.isCreatePage = true;
</script>
@endsection
