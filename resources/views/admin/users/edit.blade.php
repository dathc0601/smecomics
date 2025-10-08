@extends('admin.layout')

@section('title', 'Edit User')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Edit User</h1>
    <p class="text-gray-600">Update user: {{ $user->name }}</p>
</div>

<div class="admin-card max-w-2xl">
    <form action="{{ route('admin.users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name" class="form-label">Name *</label>
            <input type="text"
                   id="name"
                   name="name"
                   class="form-input @error('name') border-red-500 @enderror"
                   value="{{ old('name', $user->name) }}"
                   required>
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="email" class="form-label">Email *</label>
            <input type="email"
                   id="email"
                   name="email"
                   class="form-input @error('email') border-red-500 @enderror"
                   value="{{ old('email', $user->email) }}"
                   required>
            @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label class="flex items-center space-x-2">
                <input type="checkbox"
                       name="is_admin"
                       value="1"
                       class="form-checkbox"
                       {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}>
                <span class="form-label mb-0">Administrator</span>
            </label>
            <p class="text-sm text-gray-500 mt-1">Grant admin access to this user</p>
            @error('is_admin')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex space-x-4">
            <button type="submit" class="btn-admin-primary">
                Update User
            </button>
            <a href="{{ route('admin.users.index') }}" class="btn-admin-secondary">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
