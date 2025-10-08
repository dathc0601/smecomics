@extends('admin.layout')

@section('title', 'Create Manga')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Create Manga</h1>
    <p class="text-gray-600">Add a new manga to the library</p>
</div>

<div class="admin-card max-w-4xl">
    <form action="{{ route('admin.manga.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div>
                <div class="form-group">
                    <label for="title" class="form-label">Title *</label>
                    <input type="text"
                           id="title"
                           name="title"
                           class="form-input @error('title') border-red-500 @enderror"
                           value="{{ old('title') }}"
                           required>
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="slug" class="form-label">Slug *</label>
                    <input type="text"
                           id="slug"
                           name="slug"
                           class="form-input @error('slug') border-red-500 @enderror"
                           value="{{ old('slug') }}"
                           required>
                    <p class="text-sm text-gray-500 mt-1">Auto-generated from title</p>
                    @error('slug')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="author_id" class="form-label">Author</label>
                    <select id="author_id"
                            name="author_id"
                            class="form-select choices-single @error('author_id') border-red-500 @enderror">
                        <option value="">Select Author</option>
                        @foreach($authors as $author)
                            <option value="{{ $author->id }}" {{ old('author_id') == $author->id ? 'selected' : '' }}>
                                {{ $author->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('author_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="type" class="form-label">Type *</label>
                    <select id="type"
                            name="type"
                            class="form-select @error('type') border-red-500 @enderror"
                            required>
                        <option value="manhwa" {{ old('type') == 'manhwa' ? 'selected' : '' }}>Manhwa</option>
                        <option value="manga" {{ old('type') == 'manga' ? 'selected' : '' }}>Manga</option>
                        <option value="novel" {{ old('type') == 'novel' ? 'selected' : '' }}>Novel</option>
                    </select>
                    @error('type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="status" class="form-label">Status *</label>
                    <select id="status"
                            name="status"
                            class="form-select @error('status') border-red-500 @enderror"
                            required>
                        <option value="ongoing" {{ old('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="hiatus" {{ old('status') == 'hiatus' ? 'selected' : '' }}>Hiatus</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="release_year" class="form-label">Release Year</label>
                    <input type="number"
                           id="release_year"
                           name="release_year"
                           class="form-input @error('release_year') border-red-500 @enderror"
                           value="{{ old('release_year') }}"
                           min="1900"
                           max="{{ date('Y') + 1 }}">
                    @error('release_year')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Right Column -->
            <div>
                <div class="form-group">
                    <label for="cover_image" class="form-label">Cover Image</label>
                    <input type="file"
                           id="cover_image"
                           name="cover_image"
                           class="form-input @error('cover_image') border-red-500 @enderror"
                           accept="image/*"
                           data-preview="cover-preview">
                    <p class="text-sm text-gray-500 mt-1">JPG, PNG, GIF (max 2MB)</p>
                    @error('cover_image')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror

                    <img id="cover-preview" src="" alt="Preview" class="hidden mt-4 max-w-xs rounded-lg shadow-md">
                </div>

                <div class="form-group">
                    <label for="genres" class="form-label">Genres</label>
                    <select id="genres"
                            name="genres[]"
                            multiple
                            class="form-select choices-multiple @error('genres') border-red-500 @enderror">
                        @foreach($genres as $genre)
                            <option value="{{ $genre->id }}" {{ in_array($genre->id, old('genres', [])) ? 'selected' : '' }}>
                                {{ $genre->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('genres')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="tags" class="form-label">Tags</label>
                    <select id="tags"
                            name="tags[]"
                            multiple
                            class="form-select choices-multiple @error('tags') border-red-500 @enderror">
                        @foreach($tags as $tag)
                            <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', [])) ? 'selected' : '' }}>
                                {{ $tag->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('tags')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="translation_team" class="form-label">Translation Team</label>
                    <input type="text"
                           id="translation_team"
                           name="translation_team"
                           class="form-input @error('translation_team') border-red-500 @enderror"
                           value="{{ old('translation_team') }}">
                    @error('translation_team')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Checkboxes -->
                <div class="space-y-2">
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="is_featured" value="1" class="form-checkbox" {{ old('is_featured') ? 'checked' : '' }}>
                        <span class="text-sm">Featured</span>
                    </label>

                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="is_hot" value="1" class="form-checkbox" {{ old('is_hot') ? 'checked' : '' }}>
                        <span class="text-sm">Hot</span>
                    </label>

                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="is_18plus" value="1" class="form-checkbox" {{ old('is_18plus') ? 'checked' : '' }}>
                        <span class="text-sm">18+ Content</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Description (Full Width) -->
        <div class="form-group mt-6">
            <label for="description" class="form-label">Description</label>
            <textarea id="description"
                      name="description"
                      class="tinymce @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
            @error('description')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex space-x-4 mt-6">
            <button type="submit" class="btn-admin-primary">
                Create Manga
            </button>
            <a href="{{ route('admin.manga.index') }}" class="btn-admin-secondary">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
