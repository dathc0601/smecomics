@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
    <p class="text-gray-600">Welcome to the SME Comics admin panel</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="stat-card">
        <div class="stat-card-value">{{ $total_manga }}</div>
        <div class="stat-card-label">Total Manga</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-value">{{ $total_chapters }}</div>
        <div class="stat-card-label">Total Chapters</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-value">{{ $total_users }}</div>
        <div class="stat-card-label">Total Users</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-value">{{ $total_comments }}</div>
        <div class="stat-card-label">Total Comments</div>
    </div>
</div>

<!-- Recent Manga -->
<div class="admin-card mb-8">
    <h2 class="text-xl font-bold mb-4">Recent Manga</h2>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>Status</th>
                <th>Created</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recent_manga as $manga)
            <tr>
                <td>{{ $manga->title }}</td>
                <td>{{ $manga->author->name ?? 'N/A' }}</td>
                <td><span class="badge-{{ $manga->status == 'ongoing' ? 'info' : 'success' }}">{{ ucfirst($manga->status) }}</span></td>
                <td>{{ $manga->created_at->diffForHumans() }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Recent Chapters -->
<div class="admin-card">
    <h2 class="text-xl font-bold mb-4">Recent Chapters</h2>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Manga</th>
                <th>Chapter</th>
                <th>Title</th>
                <th>Created</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recent_chapters as $chapter)
            <tr>
                <td>{{ $chapter->manga->title }}</td>
                <td>Chapter {{ $chapter->chapter_number }}</td>
                <td>{{ $chapter->title ?? '-' }}</td>
                <td>{{ $chapter->created_at->diffForHumans() }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
