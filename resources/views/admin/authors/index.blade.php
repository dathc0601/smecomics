@extends('admin.layout')

@section('title', 'Authors')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Authors</h1>
        <p class="text-gray-600">Manage manga authors</p>
    </div>
    <a href="{{ route('admin.authors.create') }}" class="btn-admin-primary">
        + Add Author
    </a>
</div>

<div class="admin-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Slug</th>
                <th>Website</th>
                <th>Manga Count</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($authors as $author)
            <tr>
                <td>{{ $author->id }}</td>
                <td class="font-semibold">{{ $author->name }}</td>
                <td class="text-gray-600">{{ $author->slug }}</td>
                <td>
                    @if($author->website)
                        <a href="{{ $author->website }}" target="_blank" class="text-blue-600 hover:underline">
                            Link
                        </a>
                    @else
                        <span class="text-gray-400">-</span>
                    @endif
                </td>
                <td>
                    <span class="badge-info">{{ $author->mangas_count }} manga</span>
                </td>
                <td class="text-gray-600">{{ $author->created_at?->format('M d, Y') ?? 'N/A' }}</td>
                <td>
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.authors.edit', $author) }}" class="text-blue-600 hover:text-blue-800">
                            Edit
                        </a>
                        <form action="{{ route('admin.authors.destroy', $author) }}" method="POST" class="inline"
                              onsubmit="return confirm('Are you sure you want to delete this author?');">
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
                    No authors found. <a href="{{ route('admin.authors.create') }}" class="text-orange-manga-600 hover:underline">Create one</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($authors->hasPages())
    <div class="mt-6">
        {{ $authors->links() }}
    </div>
    @endif
</div>
@endsection
