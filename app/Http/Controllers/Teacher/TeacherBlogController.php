<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;

class TeacherBlogController extends Controller
{
    public function index(Request $request)
    {
        $teacher = auth()->user();
        
        $query = BlogPost::where('author_id', $teacher->id)
            ->with(['categories', 'tags']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                ->orWhere('content', 'like', '%' . $request->search . '%');
        }

        $posts = $query->latest()->paginate(20);

        return view('teacher.blog.index', compact('posts'));
    }
}

