<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $posts = Post::all();
        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255', 'unique:posts,title'],
            'description' => ['required', 'string'],
        ]);
        $request->user()->posts()->create($data);
        return redirect()->route('posts.index');
    }

    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        abort_unless((string) $post->user_id === (string) auth()->id(), 403);
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        abort_unless((string) $post->user_id === (string) $request->user()->id, 403);
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255', Rule::unique('posts', 'title')->ignore($post->id)],
            'description' => ['required', 'string'],
        ]);
        $post->update($data);
        return redirect()->route('posts.index');
    }

    public function destroy(Post $post)
    {
        abort_unless((string) $post->user_id === (string) auth()->id(), 403);
        $post->delete();
        return redirect()->route('posts.index');
    }
}
