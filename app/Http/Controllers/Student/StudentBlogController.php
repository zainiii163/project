<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;

class StudentBlogController extends Controller
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

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                ->orWhere('content', 'like', '%' . $request->search . '%');
        }

        $posts = $query->latest('published_at')->paginate(12);
        $categories = Category::all();

        return view('student.blog.index', compact('posts', 'categories'));
    }
}

