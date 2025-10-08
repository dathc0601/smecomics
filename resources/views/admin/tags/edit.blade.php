@extends('admin.layout')

@section('title', 'Edit Tag')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Edit Tag</h1>
    <p class="text-gray-600">Update tag: {{ $tag->name }}</p>
</div>

<div class="admin-card max-w-2xl">
    <form action="{{ route('admin.tags.update', $tag) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name" class="form-label">Tag Name *</label>
            <input type="text"
                   id="name"
                   name="name"
                   class="form-input @error('name') border-red-500 @enderror"
                   value="{{ old('name', $tag->name) }}"
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
                   value="{{ old('slug', $tag->slug) }}"
                   required>
            <p class="text-sm text-gray-500 mt-1">URL-friendly version</p>
            @error('slug')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex space-x-4">
            <button type="submit" class="btn-admin-primary">
                Update Tag
            </button>
            <a href="{{ route('admin.tags.index') }}" class="btn-admin-secondary">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
