@extends('admin.layout')

@section('title', 'Blog Categories')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Blog Categories</h1>
        <p class="text-gray-600">Manage blog categories</p>
    </div>
    <a href="{{ route('admin.blog.categories.create') }}" class="btn-admin-primary">
        + Add Category
    </a>
</div>

<div class="admin-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Slug</th>
                <th>Description</th>
                <th>Posts</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $category)
            <tr>
                <td>{{ $category->id }}</td>
                <td class="font-semibold">{{ $category->name }}</td>
                <td class="text-gray-600">{{ $category->slug }}</td>
                <td class="text-gray-600">{{ Str::limit($category->description, 50) ?? '-' }}</td>
                <td>
                    <span class="badge-info">{{ $category->posts_count }} posts</span>
                </td>
                <td class="text-gray-600">{{ $category->created_at?->format('M d, Y') ?? 'N/A' }}</td>
                <td>
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.blog.categories.edit', $category) }}" class="text-blue-600 hover:text-blue-800">
                            Edit
                        </a>
                        <form action="{{ route('admin.blog.categories.destroy', $category) }}" method="POST" class="inline"
                              onsubmit="return confirm('Are you sure you want to delete this category?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">
                                Delete
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center text-gray-500 py-8">
                    No categories found. <a href="{{ route('admin.blog.categories.create') }}" class="text-orange-manga-600 hover:underline">Create one</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($categories->hasPages())
    <div class="mt-6">
        {{ $categories->links() }}
    </div>
    @endif
</div>
@endsection
