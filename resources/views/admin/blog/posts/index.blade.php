@extends('admin.layout')

@section('title', 'Blog Posts')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Blog Posts</h1>
        <p class="text-gray-600">Manage blog articles</p>
    </div>
    <a href="{{ route('admin.blog.posts.create') }}" class="btn-admin-primary">
        + Add Post
    </a>
</div>

<!-- Filters -->
<div class="admin-card mb-6">
    <form action="{{ route('admin.blog.posts.index') }}" method="GET" class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-[200px]">
            <input type="text"
                   name="search"
                   placeholder="Search posts..."
                   class="form-input"
                   value="{{ request('search') }}">
        </div>
        <div class="w-40">
            <select name="status" class="form-select">
                <option value="">All Status</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
            </select>
        </div>
        <button type="submit" class="btn-admin-secondary">Filter</button>
        @if(request()->hasAny(['search', 'status']))
            <a href="{{ route('admin.blog.posts.index') }}" class="btn-admin-secondary">Clear</a>
        @endif
    </form>
</div>

<div class="admin-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Title</th>
                <th>Author</th>
                <th>Categories</th>
                <th>Status</th>
                <th>Views</th>
                <th>Published</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($posts as $post)
            <tr>
                <td>
                    @if($post->featured_image)
                        <img src="{{ Storage::url($post->featured_image) }}"
                             alt="{{ $post->title }}"
                             class="w-16 h-12 object-cover rounded">
                    @else
                        <div class="w-16 h-12 bg-gray-200 rounded flex items-center justify-center">
                            <span class="text-gray-400 text-xs">No image</span>
                        </div>
                    @endif
                </td>
                <td>
                    <div class="font-semibold">{{ Str::limit($post->title, 40) }}</div>
                    @if($post->is_featured)
                        <span class="badge-warning text-xs">Featured</span>
                    @endif
                </td>
                <td class="text-gray-600">
                    {{ $post->author?->name ?? 'No author' }}
                </td>
                <td>
                    @foreach($post->categories->take(2) as $category)
                        <span class="badge-info text-xs">{{ $category->name }}</span>
                    @endforeach
                    @if($post->categories->count() > 2)
                        <span class="text-gray-500 text-xs">+{{ $post->categories->count() - 2 }}</span>
                    @endif
                </td>
                <td>
                    @if($post->status === 'published')
                        <span class="badge-success">Published</span>
                    @elseif($post->status === 'scheduled')
                        <span class="badge-info">Scheduled</span>
                    @else
                        <span class="badge-warning">Draft</span>
                    @endif
                </td>
                <td class="text-gray-600">{{ number_format($post->view_count) }}</td>
                <td class="text-gray-600">
                    {{ $post->published_at?->format('M d, Y') ?? '-' }}
                </td>
                <td>
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.blog.posts.edit', $post) }}" class="text-blue-600 hover:text-blue-800">
                            Edit
                        </a>
                        <form action="{{ route('admin.blog.posts.destroy', $post) }}" method="POST" class="inline"
                              onsubmit="return confirm('Are you sure you want to delete this post?');">
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
                <td colspan="8" class="text-center text-gray-500 py-8">
                    No blog posts found. <a href="{{ route('admin.blog.posts.create') }}" class="text-orange-manga-600 hover:underline">Create one</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($posts->hasPages())
    <div class="mt-6">
        {{ $posts->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
