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
        $post = new Post($data);
        $post->user()->associate($request->user());
        $post->save();
        return redirect()->route('posts.index');
    }

    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        $this->requireOwner($post);
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $this->requireOwner($post);
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255', Rule::unique('posts', 'title')->ignore($post)],
            'description' => ['required', 'string'],
        ]);
        $post->update($data);
        return redirect()->route('posts.index');
    }

    public function destroy(Post $post)
    {
        $this->requireOwner($post);
        $post->delete();
        return redirect()->route('posts.index');
    }

    private function requireOwner(Post $post): void
    {
        abort_unless((string) $post->user_id === (string) auth()->id(), 403);
    }
}
