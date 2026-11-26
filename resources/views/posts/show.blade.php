@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>{{ $post->title }}</h3>
        </div>
        <div class="card-body">
            <p>{{ $post->description }}</p>
        </div>
        @if ((string) $post->user_id === (string) auth()->id())
        <div class="card-footer">
            <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-warning">تعديل</a>
            <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">حذف</button>
            </form>
        </div>
        @endif
    </div>
</div>
@endsection
