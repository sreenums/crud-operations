@extends('layout')

@section('title', 'Home posts')

@section('content')

<div class="container mt-3">
    <br>
    <div class="text-right mt-5">
      <a href=" {{ route('posts.create') }} " class="btn btn-outline-success">Add New Post</a>
    </div>
    <form method="POST" id="searchPosts" name="searchPosts" action="#" >
      <div class="input-group mt-3 w-40">
        <input type="text" class="form-control" id="advanced-search-input" placeholder="Author, title or content" />

          <select id="checkActive" name="checkActive" class="form-select ml-3 mr-3">
            <option selected value="">-- Status --</option>
            <option value="1">Active</option>
            <option value="0">Inactive</option>
          </select>
          <input type="date" id="datePublished" class="form-control" name="datePublished">

        <button class="btn btn-primary" id="advanced-search-button" type="submit">
          <i class="fa fa-search"></i>
        </button>
      </div>
    </form>
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
          <td>{{ $post->published_at_formatted; }}</td>
          <td><a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#exampleModal{{ $post->id }}"> {{ $post->comments->count() }} (View) </a></td>
          <td>
            {!! $post->status_text !!}
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

        <div class="modal fade" id="exampleModal{{ $post->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">{{ $post->title }} - Comments</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>

                  @forelse ($post->comments as $comment)
                  <title>Comment {{ $loop->iteration }}:</title>
                    <div class="modal-body">
                      <p>{{$comment->comment}}</p>
                    </div>
                  @empty
                    <div class="modal-body">
                        <p class="no-data-message">No comments Added.</p>
                    </div>
                  @endforelse

                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  </div>
              </div>
          </div>
        </div>
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
