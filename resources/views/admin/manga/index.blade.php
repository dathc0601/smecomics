@extends('admin.layout')

@section('title', 'Manga')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Manga</h1>
        <p class="text-gray-600">Manage all manga</p>
    </div>
    <a href="{{ route('admin.manga.create') }}" class="btn-admin-primary">
        + Add Manga
    </a>
</div>

<!-- Search & Filter -->
<div class="admin-card mb-6">
    <form action="{{ route('admin.manga.index') }}" method="GET" class="flex gap-4">
        <input type="text"
               name="search"
               class="form-input flex-1"
               placeholder="Search manga by title..."
               value="{{ request('search') }}">

        <select name="status" class="form-select w-48">
            <option value="">All Status</option>
            <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
            <option value="hiatus" {{ request('status') == 'hiatus' ? 'selected' : '' }}>Hiatus</option>
        </select>

        <button type="submit" class="btn-admin-primary">
            Filter
        </button>

        @if(request('search') || request('status'))
        <a href="{{ route('admin.manga.index') }}" class="btn-admin-secondary">
            Clear
        </a>
        @endif
    </form>
</div>

<div class="admin-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Cover</th>
                <th>Title</th>
                <th>Author</th>
                <th>Type</th>
                <th>Status</th>
                <th>Genres</th>
                <th>Chapters</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($mangas as $manga)
            <tr>
                <td>
                    @if($manga->cover_image)
                        <img src="{{ asset('storage/' . $manga->cover_image) }}"
                             alt="{{ $manga->title }}"
                             class="w-12 h-16 object-cover rounded">
                    @else
                        <div class="w-12 h-16 bg-gray-200 rounded flex items-center justify-center text-gray-400 text-xs">
                            No Image
                        </div>
                    @endif
                </td>
                <td class="font-semibold">
                    {{ $manga->title }}
                    @if($manga->is_featured)
                        <span class="badge-warning ml-1">Featured</span>
                    @endif
                    @if($manga->is_hot)
                        <span class="badge-danger ml-1">Hot</span>
                    @endif
                    @if($manga->is_18plus)
                        <span class="badge-danger ml-1">18+</span>
                    @endif
                </td>
                <td class="text-gray-600">{{ $manga->author?->name ?? 'Unknown' }}</td>
                <td class="capitalize">{{ $manga->type }}</td>
                <td>
                    @if($manga->status == 'ongoing')
                        <span class="badge-info">Ongoing</span>
                    @elseif($manga->status == 'completed')
                        <span class="badge-success">Completed</span>
                    @else
                        <span class="badge-warning">Hiatus</span>
                    @endif
                </td>
                <td class="text-sm">
                    @foreach($manga->genres->take(2) as $genre)
                        <span class="inline-block bg-gray-100 px-2 py-1 rounded text-xs mr-1">{{ $genre->name }}</span>
                    @endforeach
                    @if($manga->genres->count() > 2)
                        <span class="text-gray-500">+{{ $manga->genres->count() - 2 }}</span>
                    @endif
                </td>
                <td>{{ $manga->chapters_count ?? $manga->chapters->count() ?? 0 }}</td>
                <td>
                    <div class="flex flex-col space-y-1">
                        <a href="{{ route('admin.manga.edit', $manga) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                            Edit
                        </a>
                        <form action="{{ route('admin.manga.destroy', $manga) }}" method="POST"
                              onsubmit="return confirm('Are you sure? This will delete all chapters too!');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                Delete
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center text-gray-500 py-8">
                    No manga found. <a href="{{ route('admin.manga.create') }}" class="text-orange-manga-600 hover:underline">Create one</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($mangas->hasPages())
    <div class="mt-6">
        {{ $mangas->links() }}
    </div>
    @endif
</div>
@endsection
