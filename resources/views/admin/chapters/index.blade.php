@extends('admin.layout')

@section('title', 'Chapters')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Chapters</h1>
        <p class="text-gray-600">Manage manga chapters</p>
    </div>
    <a href="{{ route('admin.chapters.create') }}" class="btn-admin-primary">
        + Add Chapter
    </a>
</div>

<!-- Filter by Manga -->
<div class="admin-card mb-6">
    <form action="{{ route('admin.chapters.index') }}" method="GET" class="flex gap-4">
        <select name="manga_id" class="form-select flex-1">
            <option value="">All Manga</option>
            @foreach($mangas as $m)
                <option value="{{ $m->id }}" {{ request('manga_id') == $m->id ? 'selected' : '' }}>
                    {{ $m->title }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="btn-admin-primary">
            Filter
        </button>

        @if(request('manga_id'))
        <a href="{{ route('admin.chapters.index') }}" class="btn-admin-secondary">
            Clear
        </a>
        @endif
    </form>
</div>

<div class="admin-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Manga</th>
                <th>Title</th>
                <th>Images</th>
                <th>Views</th>
                <th>Published</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($chapters as $chapter)
            <tr>
                <td>{{ $chapter->id }}</td>
                <td class="font-semibold text-sm">{{ $chapter->manga->title }}</td>
                <td class="text-gray-600">{{ $chapter->title ?: '-' }}</td>
                <td>
                    <span class="badge-info">{{ $chapter->images_count ?? $chapter->images->count() ?? 0 }} images</span>
                </td>
                <td>{{ number_format($chapter->view_count) }}</td>
                <td class="text-gray-600 text-sm">{{ $chapter->published_at?->format('M d, Y') ?? 'N/A' }}</td>
                <td>
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.chapters.edit', $chapter) }}" class="text-blue-600 hover:text-blue-800">
                            Edit
                        </a>
                        <form action="{{ route('admin.chapters.destroy', $chapter) }}" method="POST" class="inline"
                              onsubmit="return confirm('Are you sure you want to delete this chapter?');">
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
                    No chapters found. <a href="{{ route('admin.chapters.create') }}" class="text-orange-manga-600 hover:underline">Create one</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($chapters->hasPages())
    <div class="mt-6">
        {{ $chapters->links() }}
    </div>
    @endif
</div>
@endsection
