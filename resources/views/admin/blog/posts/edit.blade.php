@extends('admin.layout')

@section('title', 'Edit Blog Post')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Edit Blog Post</h1>
    <p class="text-gray-600">Update article: {{ $post->title }}</p>
</div>

<form action="{{ route('admin.blog.posts.update', $post) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content Column -->
        <div class="lg:col-span-2 space-y-6">
            <div class="admin-card">
                <h2 class="text-lg font-semibold mb-4">Content</h2>

                <div class="form-group">
                    <label for="title" class="form-label">Title *</label>
                    <input type="text"
                           id="title"
                           name="title"
                           class="form-input @error('title') border-red-500 @enderror"
                           value="{{ old('title', $post->title) }}"
                           required>
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="slug" class="form-label">Slug *</label>
                    <input type="text"
                           id="slug"
                           name="slug"
                           class="form-input @error('slug') border-red-500 @enderror"
                           value="{{ old('slug', $post->slug) }}"
                           required>
                    <p class="text-sm text-gray-500 mt-1">URL: /blogs/{{ $post->slug }}</p>
                    @error('slug')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="excerpt" class="form-label">Excerpt</label>
                    <textarea id="excerpt"
                              name="excerpt"
                              rows="3"
                              class="form-input @error('excerpt') border-red-500 @enderror"
                              placeholder="Brief summary of the post (max 500 characters)">{{ old('excerpt', $post->excerpt) }}</textarea>
                    @error('excerpt')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="content" class="form-label">Content *</label>
                    <textarea id="content"
                              name="content"
                              class="tinymce @error('content') border-red-500 @enderror">{{ old('content', $post->content) }}</textarea>
                    @error('content')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- SEO Section -->
            <div class="admin-card" x-data="{ open: false }">
                <button type="button" @click="open = !open" class="w-full flex justify-between items-center text-lg font-semibold">
                    <span>SEO Settings</span>
                    <svg class="w-5 h-5 transform transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div x-show="open" x-collapse class="mt-4 space-y-4">
                    <div class="form-group">
                        <label for="meta_title" class="form-label">Meta Title</label>
                        <input type="text"
                               id="meta_title"
                               name="meta_title"
                               class="form-input @error('meta_title') border-red-500 @enderror"
                               value="{{ old('meta_title', $post->meta_title) }}"
                               maxlength="60"
                               placeholder="SEO title (max 60 characters)">
                        @error('meta_title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="meta_description" class="form-label">Meta Description</label>
                        <textarea id="meta_description"
                                  name="meta_description"
                                  rows="2"
                                  class="form-input @error('meta_description') border-red-500 @enderror"
                                  maxlength="160"
                                  placeholder="SEO description (max 160 characters)">{{ old('meta_description', $post->meta_description) }}</textarea>
                        @error('meta_description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="meta_keywords" class="form-label">Meta Keywords</label>
                        <input type="text"
                               id="meta_keywords"
                               name="meta_keywords"
                               class="form-input @error('meta_keywords') border-red-500 @enderror"
                               value="{{ old('meta_keywords', $post->meta_keywords) }}"
                               placeholder="keyword1, keyword2, keyword3">
                        @error('meta_keywords')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="og_image" class="form-label">Open Graph Image</label>
                        @if($post->og_image)
                            <div class="mb-2">
                                <img src="{{ Storage::url($post->og_image) }}" alt="Current OG Image" class="max-w-xs rounded-lg shadow-md">
                            </div>
                        @endif
                        <input type="file"
                               id="og_image"
                               name="og_image"
                               class="form-input @error('og_image') border-red-500 @enderror"
                               accept="image/*"
                               data-preview="og-preview">
                        <p class="text-sm text-gray-500 mt-1">Recommended: 1200x630px</p>
                        @error('og_image')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <img id="og-preview" src="" alt="Preview" class="hidden mt-4 max-w-xs rounded-lg shadow-md">
                    </div>
                </div>
            </div>

            <!-- Revision History -->
            @if($post->revisions->count() > 0)
            <div class="admin-card" x-data="{ open: false }">
                <button type="button" @click="open = !open" class="w-full flex justify-between items-center text-lg font-semibold">
                    <span>Revision History ({{ $post->revisions->count() }})</span>
                    <svg class="w-5 h-5 transform transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div x-show="open" x-collapse class="mt-4">
                    <div class="space-y-3">
                        @foreach($post->revisions as $revision)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-700">{{ Str::limit($revision->title, 50) }}</p>
                                <p class="text-sm text-gray-500">
                                    {{ $revision->created_at->format('M d, Y H:i') }}
                                    @if($revision->user)
                                        by {{ $revision->user->name }}
                                    @endif
                                </p>
                            </div>
                            <span class="badge-info text-xs">
                                {{ $revision->metadata['status'] ?? 'N/A' }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar Column -->
        <div class="space-y-6">
            <!-- Publishing -->
            <div class="admin-card">
                <h2 class="text-lg font-semibold mb-4">Publishing</h2>

                <div class="form-group">
                    <label for="status" class="form-label">Status *</label>
                    <select id="status"
                            name="status"
                            class="form-select @error('status') border-red-500 @enderror"
                            required
                            x-data
                            @change="$dispatch('status-changed', $el.value)">
                        <option value="draft" {{ old('status', $post->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $post->status) == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="scheduled" {{ old('status', $post->status) == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group" x-data="{ show: '{{ old('status', $post->status) }}' === 'scheduled' }" @status-changed.window="show = ($event.detail === 'scheduled')">
                    <div x-show="show" x-transition>
                        <label for="published_at" class="form-label">Schedule Date</label>
                        <input type="datetime-local"
                               id="published_at"
                               name="published_at"
                               class="form-input @error('published_at') border-red-500 @enderror"
                               value="{{ old('published_at', $post->published_at?->format('Y-m-d\TH:i')) }}">
                        @error('published_at')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                @if($post->status === 'published' && $post->published_at)
                <div class="text-sm text-gray-500 mb-4">
                    Published: {{ $post->published_at->format('M d, Y H:i') }}
                </div>
                @endif

                <div class="form-group">
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="is_featured" value="1" class="form-checkbox" {{ old('is_featured', $post->is_featured) ? 'checked' : '' }}>
                        <span class="text-sm">Featured Post</span>
                    </label>
                </div>

                <div class="text-sm text-gray-500 pt-2 border-t">
                    <p>Views: {{ number_format($post->view_count) }}</p>
                    <p>Created: {{ $post->created_at->format('M d, Y H:i') }}</p>
                    <p>Updated: {{ $post->updated_at->format('M d, Y H:i') }}</p>
                </div>
            </div>

            <!-- Featured Image -->
            <div class="admin-card">
                <h2 class="text-lg font-semibold mb-4">Featured Image</h2>

                @if($post->featured_image)
                    <div class="mb-4">
                        <img src="{{ Storage::url($post->featured_image) }}" alt="Current featured image" class="w-full rounded-lg shadow-md">
                    </div>
                @endif

                <div class="form-group">
                    <input type="file"
                           id="featured_image"
                           name="featured_image"
                           class="form-input @error('featured_image') border-red-500 @enderror"
                           accept="image/*"
                           data-preview="featured-preview">
                    <p class="text-sm text-gray-500 mt-1">JPG, PNG, WebP (max 5MB)</p>
                    @error('featured_image')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <img id="featured-preview" src="" alt="Preview" class="hidden mt-4 w-full rounded-lg shadow-md">
                </div>
            </div>

            <!-- Taxonomy -->
            <div class="admin-card">
                <h2 class="text-lg font-semibold mb-4">Organization</h2>

                <div class="form-group">
                    <label for="author_id" class="form-label">Author</label>
                    <select id="author_id"
                            name="author_id"
                            class="form-select choices-single @error('author_id') border-red-500 @enderror">
                        <option value="">Select Author</option>
                        @foreach($authors as $author)
                            <option value="{{ $author->id }}" {{ old('author_id', $post->author_id) == $author->id ? 'selected' : '' }}>
                                {{ $author->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('author_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="categories" class="form-label">Categories</label>
                    <select id="categories"
                            name="categories[]"
                            multiple
                            class="form-select choices-multiple @error('categories') border-red-500 @enderror">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ in_array($category->id, old('categories', $post->categories->pluck('id')->toArray())) ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('categories')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="tags" class="form-label">Tags</label>
                    <select id="tags"
                            name="tags[]"
                            multiple
                            class="form-select choices-multiple @error('tags') border-red-500 @enderror">
                        @foreach($tags as $tag)
                            <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', $post->tags->pluck('id')->toArray())) ? 'selected' : '' }}>
                                {{ $tag->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('tags')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="flex space-x-4 mt-6">
        <button type="submit" class="btn-admin-primary">
            Update Post
        </button>
        <a href="{{ route('admin.blog.posts.index') }}" class="btn-admin-secondary">
            Cancel
        </a>
    </div>
</form>
@endsection
