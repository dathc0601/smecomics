@extends('admin.layout')

@section('title', 'Edit Author')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Edit Author</h1>
    <p class="text-gray-600">Update author: {{ $author->name }}</p>
</div>

<div class="admin-card max-w-2xl">
    <form action="{{ route('admin.authors.update', $author) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name" class="form-label">Author Name *</label>
            <input type="text"
                   id="name"
                   name="name"
                   class="form-input @error('name') border-red-500 @enderror"
                   value="{{ old('name', $author->name) }}"
                   required>
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="slug" class="form-label">Slug *</label>
            <input type="text"
                   id="slug"
                   name="slug"
                   class="form-input @error('slug') border-red-500 @enderror"
                   value="{{ old('slug', $author->slug) }}"
                   required>
            <p class="text-sm text-gray-500 mt-1">URL-friendly version</p>
            @error('slug')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="bio" class="form-label">Biography</label>
            <textarea id="bio"
                      name="bio"
                      rows="4"
                      class="form-textarea @error('bio') border-red-500 @enderror">{{ old('bio', $author->bio) }}</textarea>
            @error('bio')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="website" class="form-label">Website</label>
            <input type="url"
                   id="website"
                   name="website"
                   class="form-input @error('website') border-red-500 @enderror"
                   value="{{ old('website', $author->website) }}"
                   placeholder="https://example.com">
            @error('website')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex space-x-4">
            <button type="submit" class="btn-admin-primary">
                Update Author
            </button>
            <a href="{{ route('admin.authors.index') }}" class="btn-admin-secondary">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
