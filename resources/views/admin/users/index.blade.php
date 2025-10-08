@extends('admin.layout')

@section('title', 'Users')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Users</h1>
    <p class="text-gray-600">Manage user accounts</p>
</div>

<!-- Search Form -->
<div class="admin-card mb-6">
    <form action="{{ route('admin.users.index') }}" method="GET" class="flex gap-4">
        <input type="text"
               name="search"
               class="form-input flex-1"
               placeholder="Search by name or email..."
               value="{{ request('search') }}">
        <button type="submit" class="btn-admin-primary">
            Search
        </button>
        @if(request('search'))
        <a href="{{ route('admin.users.index') }}" class="btn-admin-secondary">
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
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Joined</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td class="font-semibold">{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @if($user->is_admin)
                        <span class="badge-danger">Admin</span>
                    @else
                        <span class="badge-info">User</span>
                    @endif
                </td>
                <td class="text-gray-600">{{ $user->created_at?->format('M d, Y') ?? 'N/A' }}</td>
                <td>
                    <div class="flex space-x-2">
                        <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-600 hover:text-blue-800">
                            Edit
                        </a>

                        <form action="{{ route('admin.users.toggle-admin', $user) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-green-600 hover:text-green-800">
                                {{ $user->is_admin ? 'Remove Admin' : 'Make Admin' }}
                            </button>
                        </form>

                        @if($user->id !== auth()->id())
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline"
                              onsubmit="return confirm('Are you sure you want to delete this user?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">
                                Delete
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center text-gray-500 py-8">
                    No users found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($users->hasPages())
    <div class="mt-6">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection
