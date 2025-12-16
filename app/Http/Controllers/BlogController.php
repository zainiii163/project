<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = BlogPost::with(['author', 'categories', 'tags'])
            ->where('status', 'published');

        if ($request->has('category')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
        }

        if ($request->has('tag')) {
            $query->whereHas('tags', function($q) use ($request) {
                $q->where('tags.id', $request->tag);
            });
        }

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                ->orWhere('content', 'like', '%' . $request->search . '%');
        }

        $posts = $query->latest('published_at')->paginate(12);
        $categories = Category::all();
        $tags = Tag::all();

        return view('blog.index', compact('posts', 'categories', 'tags'));
    }

    public function show($slug)
    {
        $post = BlogPost::with(['author', 'categories', 'tags'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $recentPosts = BlogPost::where('status', 'published')
            ->where('id', '!=', $post->id)
            ->latest('published_at')
            ->take(5)
            ->get();

        return view('blog.show', compact('post', 'recentPosts'));
    }

    public function create()
    {
        $this->authorize('create', BlogPost::class);
        $categories = Category::all();
        $tags = Tag::all();
        return view('blog.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', BlogPost::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'featured_image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published',
            'category_ids' => 'nullable|array',
            'tag_ids' => 'nullable|array',
        ]);

        $validated['author_id'] = auth()->id();
        $validated['slug'] = Str::slug($validated['title']);
        
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('blog', 'public');
        }

        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        $post = BlogPost::create($validated);

        if ($request->has('category_ids')) {
            $post->categories()->sync($request->category_ids);
        }

        if ($request->has('tag_ids')) {
            $post->tags()->sync($request->tag_ids);
        }

        return redirect()->route('blog.show', $post->slug)
            ->with('success', 'Blog post created successfully!');
    }

    public function edit(BlogPost $post)
    {
        $this->authorize('update', $post);
        $categories = Category::all();
        $tags = Tag::all();
        return view('blog.edit', compact('post', 'categories', 'tags'));
    }

    public function update(Request $request, BlogPost $post)
    {
        $this->authorize('update', $post);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'featured_image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published',
            'category_ids' => 'nullable|array',
            'tag_ids' => 'nullable|array',
        ]);

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('blog', 'public');
        }

        if ($validated['status'] === 'published' && !$post->published_at) {
            $validated['published_at'] = now();
        }

        $post->update($validated);

        if ($request->has('category_ids')) {
            $post->categories()->sync($request->category_ids);
        } else {
            $post->categories()->detach();
        }

        if ($request->has('tag_ids')) {
            $post->tags()->sync($request->tag_ids);
        } else {
            $post->tags()->detach();
        }

        return redirect()->route('blog.show', $post->slug)
            ->with('success', 'Blog post updated successfully!');
    }

    public function destroy(BlogPost $post)
    {
        $this->authorize('delete', $post);
        $post->delete();

        return redirect()->route('blog.index')
            ->with('success', 'Blog post deleted successfully!');
    }
}

