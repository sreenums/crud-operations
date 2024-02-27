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
          <th>Comments Count</th>
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
          <td width="400px">{{ $post->title; }}</td>
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
            <a href=" {{ route('posts.show', ['post' => $post->id]) }} " id="view-user" class="btn btn-primary"> View </a>
          </td>
          <td>
            <a href="{{ route('posts.edit', ['post' => $post->id]) }} " id="edit-user" class="btn btn-secondary"> Edit </a>
          </td>
          <td>
            <a href="javascript:void(0)" id="delete-post" data-url="{{ route('posts.destroy', ['post' => $post->id]) }}" class="btn btn-danger"> Delete </a>
          </td>
        </tr>
        @endforeach
  
      </tbody>
    </table>
</div>

<script type="text/javascript">

  $(document).ready(function () {

      $('body').on('click', '#delete-post', function () {

        var postURL = $(this).data('url');
        var trObj = $(this);
        if(confirm("Are you sure you want to delete this post?") == true){

              $.ajax({
                  url: postURL,
                  type: 'DELETE',
                  dataType: 'json',

                  success: function(data) {
                      alert(data.success);
                      trObj.parents("tr").remove();
                  }
              });
        }
     });

  });
</script>

@endsection
