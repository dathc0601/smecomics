@extends('admin.layout')

@section('title', 'Create Blog Category')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Create Blog Category</h1>
    <p class="text-gray-600">Add a new blog category</p>
</div>

<div class="admin-card max-w-2xl">
    <form action="{{ route('admin.blog.categories.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="name" class="form-label">Category Name *</label>
            <input type="text"
                   id="name"
                   name="name"
                   class="form-input @error('name') border-red-500 @enderror"
                   value="{{ old('name') }}"
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
                   value="{{ old('slug') }}"
                   required>
            <p class="text-sm text-gray-500 mt-1">URL-friendly version (auto-generated from name)</p>
            @error('slug')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="description" class="form-label">Description</label>
            <textarea id="description"
                      name="description"
                      rows="3"
                      class="form-input @error('description') border-red-500 @enderror"
                      placeholder="Brief description of the category (optional)">{{ old('description') }}</textarea>
            @error('description')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex space-x-4">
            <button type="submit" class="btn-admin-primary">
                Create Category
            </button>
            <a href="{{ route('admin.blog.categories.index') }}" class="btn-admin-secondary">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
