@extends('layout')

@section('title', 'View User Address')

@section('content')

<br>
<div class="container mt-3 max-tb-width">
  <br>
  <div class="mt-3 mb-3">
    <a href="{{ route('posts.index') }}" class="btn btn-dark">Back</a>
  </div>
  
  @if (session('success'))
    <p>
      <div class="alert alert-success">
        {{ session('success') }}
      </div>
    </p>
  @endif

  <article>
      <h2>{{ $post->title; }}</h2>
      <p>
          By {{ $user->name }} , &nbsp; published on {{ $post->published_at_formatted }}
      </p>
      <p>
        Status : {{ $post->status_text }}
      </p>
      <p>
          {{ $post->content; }}
      </p>

      @if ($post->image)
      <p>
        <img src="{{ asset('images/' . $post->image) }}" alt="User Image" class="user-image">
      </p>
      @endif

      <form method="POST" id="commentForm" name="commentForm" class="was-validated" action="{{ route('comment.save',['postId' => $post->id ]) }}">
        @csrf
        <label for="comment" class="form-label">Comment:</label>
        <input type="text" class="form-control" id="comment" name="comment" maxlength="250">
        <button type="submit" class="btn btn-outline-primary mt-3">Add Comment</button>
      </form>

  </article>

</div>

@endsection