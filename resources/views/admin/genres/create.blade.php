@extends('admin.layout')

@section('title', 'Create Genre')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Create Genre</h1>
    <p class="text-gray-600">Add a new manga genre</p>
</div>

<div class="admin-card max-w-2xl">
    <form action="{{ route('admin.genres.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="name" class="form-label">Genre Name *</label>
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

        <div class="flex space-x-4">
            <button type="submit" class="btn-admin-primary">
                Create Genre
            </button>
            <a href="{{ route('admin.genres.index') }}" class="btn-admin-secondary">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
