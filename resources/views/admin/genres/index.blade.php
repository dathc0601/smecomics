@extends('admin.layout')

@section('title', 'Genres')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Genres</h1>
        <p class="text-gray-600">Manage manga genres</p>
    </div>
    <a href="{{ route('admin.genres.create') }}" class="btn-admin-primary">
        + Add Genre
    </a>
</div>

<div class="admin-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Slug</th>
                <th>Manga Count</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($genres as $genre)
            <tr>
                <td>{{ $genre->id }}</td>
                <td class="font-semibold">{{ $genre->name }}</td>
                <td class="text-gray-600">{{ $genre->slug }}</td>
                <td>
                    <span class="badge-info">{{ $genre->mangas_count }} manga</span>
                </td>
                <td class="text-gray-600">{{ $genre->created_at?->format('M d, Y') ?? 'N/A' }}</td>
                <td>
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.genres.edit', $genre) }}" class="text-blue-600 hover:text-blue-800">
                            Edit
                        </a>
                        <form action="{{ route('admin.genres.destroy', $genre) }}" method="POST" class="inline"
                              onsubmit="return confirm('Are you sure you want to delete this genre?');">
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
                <td colspan="6" class="text-center text-gray-500 py-8">
                    No genres found. <a href="{{ route('admin.genres.create') }}" class="text-orange-manga-600 hover:underline">Create one</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($genres->hasPages())
    <div class="mt-6">
        {{ $genres->links() }}
    </div>
    @endif
</div>
@endsection
