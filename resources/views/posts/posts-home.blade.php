@extends('layout')

@section('title', 'Home posts')

@section('content')

<div class="container mt-3">
    <br>
    <div class="text-right mt-5">
      <a href=" {{ route('posts.create') }} " class="btn btn-outline-success">Add New Post</a>
    </div>
    <br>
    <h2>Posts List</h2>
    <table class="table table-hover">
      <thead class="table-success">


        <tr>
          <th>Sl.No</th>
          <th>Title</th>
          <th>Authour</th>
          <th>Date Published</th>
          <th>Comment Count</th>
          <th>Status</th>
          <th></th>
          <th></th>
          <th></th>
        </tr>

      </thead>
      <tbody>

        @foreach ($posts as $post)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $post->title; }}</td>
          <td> {{ $post->user->name }} </td>
          <td>{{ $post->date_published; }}</td>
          <td><a href="#"> 3 (View) </a></td>
          <td>
            @if ($post->is_active == '1')
              {{ "Active" }}
            @else
              {{ "In-active" }}
            @endif
          </td>
          <td>
            <a href="#" id="delete-user" class="btn btn-primary"> View </a>
          </td>
          <td>
            <a href="#" id="delete-user" class="btn btn-secondary"> Edit </a>
          </td>
          <td>
            <a href="javascript:void(0)" id="delete-user" data-url="#" class="btn btn-danger"> Delete </a>
          </td>
        </tr>
        @endforeach
  
      </tbody>
    </table>
</div>


@endsection
