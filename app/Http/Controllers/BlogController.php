<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Tag;

class BlogController extends Controller
{
    public function index()
    {
        $posts = BlogPost::with(['author', 'categories'])
            ->published()
            ->latest('published_at')
            ->paginate(12);

        $featuredPosts = BlogPost::with(['author', 'categories'])
            ->published()
            ->featured()
            ->latest('published_at')
            ->take(3)
            ->get();

        $categories = BlogCategory::withCount(['posts' => function ($q) {
            $q->published();
        }])->get();

        $popularTags = Tag::withCount(['blogPosts' => function ($q) {
            $q->published();
        }])->orderByDesc('blog_posts_count')->take(15)->get();

        return view('blog.index', compact('posts', 'featuredPosts', 'categories', 'popularTags'));
    }

    public function show(BlogPost $post)
    {
        // Only show published posts
        if (!$post->is_published) {
            abort(404);
        }

        $post->load(['author', 'categories', 'tags']);
        $post->incrementViewCount();

        $relatedPosts = BlogPost::with(['author', 'categories'])
            ->published()
            ->where('id', '!=', $post->id)
            ->whereHas('categories', function ($q) use ($post) {
                $q->whereIn('blog_categories.id', $post->categories->pluck('id'));
            })
            ->latest('published_at')
            ->take(4)
            ->get();

        $recentPosts = BlogPost::with(['author'])
            ->published()
            ->where('id', '!=', $post->id)
            ->latest('published_at')
            ->take(5)
            ->get();

        return view('blog.show', compact('post', 'relatedPosts', 'recentPosts'));
    }

    public function category(BlogCategory $category)
    {
        $posts = $category->publishedPosts()
            ->with(['author', 'categories'])
            ->latest('published_at')
            ->paginate(12);

        $categories = BlogCategory::withCount(['posts' => function ($q) {
            $q->published();
        }])->get();

        return view('blog.category', compact('category', 'posts', 'categories'));
    }

    public function tag(Tag $tag)
    {
        $posts = $tag->blogPosts()
            ->with(['author', 'categories'])
            ->published()
            ->latest('published_at')
            ->paginate(12);

        $categories = BlogCategory::withCount(['posts' => function ($q) {
            $q->published();
        }])->get();

        return view('blog.tag', compact('tag', 'posts', 'categories'));
    }

    public function author(Author $author)
    {
        $posts = $author->blogPosts()
            ->with(['categories'])
            ->published()
            ->latest('published_at')
            ->paginate(12);

        $categories = BlogCategory::withCount(['posts' => function ($q) {
            $q->published();
        }])->get();

        return view('blog.author', compact('author', 'posts', 'categories'));
    }
}
