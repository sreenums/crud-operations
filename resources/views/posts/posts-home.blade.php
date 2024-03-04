@extends('layout')

@section('title', 'Home posts')

@section('content')

<div class="container mt-3">
    <br>
    <div class="text-right mt-5">
      <a href=" {{ route('posts.create') }} " class="btn btn-outline-success">Add New Post</a>
    </div>
    <form method="POST" id="searchPosts" name="searchPosts" action="#" >
      @csrf
      <div class="container mt-5">
          <div class="row">
              <div class="col-md-3">
                  <!-- Search Input -->
                  <div class="form-group">
                      <label for="search">Search:</label>
                      <input type="text" id="search" name="search" class="form-control" placeholder="Title or content" value="{{ request('search') }}">
                  </div>
              </div>
              <div class="col-md-3">
                  <!-- Author Filter -->
                  <div class="form-group">
                      <label for="author">Author:</label>
                      <select id="author" name="author" class="form-control">
                          <option value="all">All Authors</option>
                          @foreach($userNames as $authorId => $authorName)
                              <option value="{{ $authorName->id }}" {{ request('author') == $authorName->id ? 'selected' : 'all' }}>{{ $authorName->name }}</option>
                          @endforeach
                      </select>
                  </div>
              </div>
              <div class="col-md-3">
                  <!-- Status Filter -->
                  <div class="form-group">
                      <label for="status">Status:</label>
                      <select id="status" name="status" class="form-control">
                          <option value="all">All Statuses</option>
                          <option value="1" {{ request('status') == "1" ? 'selected' : 'all' }} >Active</option>
                          <option value="0" {{ request('status') == "0" ? 'selected' : 'all' }}>Inactive</option>
                      </select>
                  </div>
              </div>
              <div class="col-md-3">
                  <!-- Search Input Count -->
                  <div class="form-group">
                      <label for="commentsCount">Comments count:</label>
                      <input type="text" id="commentsCount" name="commentsCount" class="form-control" onkeypress="return /[0-9]/i.test(event.key)" placeholder="Count" value="{{ request('commentsCount') }}">
                  </div>
              </div>
          </div>
          <!-- Search Results Section -->
          <div class="row mt-3">
              <div class="col-md-12">
                  <h4>Search Results:</h4>
                  <!--<ul id="searchResults"></ul>-->
                  <table id="searchResultsTable" class="table table-hover">
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
                    <tbody id="searchResults">



                    @foreach ($posts as $post)
                        <tr>
                        <!--<td>{{ $loop->iteration }}</td>-->
                        <td>{{ $posts->firstItem() + $loop->index }} </td>
                        <td width="400px">{{ $post->title; }}</td>
                        <td> {{ $post->user->name }} </td>
                        <td>{{ $post->published_at_formatted; }}</td>
                        <td>
                            <a href="#" class="view-comments" data-post-id="{{ $post->id }}"> {{ $post->comments_count }} View</a>
                        </td>
                        <td>
                            {{ $post->status_text }}
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
                <div id="pagination" class="mt-3">
                    {{ $posts->links() }}
                    <!-- Pagination links will be inserted here dynamically -->
                </div>
              </div>
          </div>
      </div>

    </form>

    <br>
    <!--<h2>Posts List</h2>-->
    <table class="table table-hover">
      <!--<thead class="table-success">
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
      </thead>-->

      <tbody>

        
        <!--Comments popup -->
        <div class="modal fade" id="commentsModal" tabindex="-1" aria-labelledby="commentsModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="commentsModalLabel">Comments</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body" id="commentsContainer">
                      <!-- Comments will be loaded here -->
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  </div>
              </div>
          </div>
        </div>

      </tbody>

    </table>
    <div>
        
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function () {
        // Perform AJAX request on filter change or search input
        $('#author, #status, #search, #commentsCount').on('keyup change', function () {

            $.ajax({
                url: '/search',
                type: 'GET',
                data: {
                    author: $('#author').val(),
                    status: $('#status').val(),
                    search: $('#search').val(),
                    commentsCount: $('#commentsCount').val(),
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                  //console.log('Response:', response);
                    // Update search results
                    $('#searchResults').empty();
                    $.each(response.data, function (index, post) {
                        console.log(response);
                        if(post.is_active == '1'){ 
                            active="Active"; 
                        } else{ 
                            active="Inactive"; 
                        }
                        cnt = index +1;

                         $('#searchResults').append('<tr><td width="50px">'+ cnt +'</td><td>' + post.title + '</td><td>'+ post.user.name +'</td><td>'+ post.date_published+'</td><td><a href="#" class="view-comments" data-post-id="'+post.id+'">'+ post.comments_count +' (View)</a></td><td>'+ active +'</td></tr>');

                    });

                    $('#pagination').html(response.links);
                }
            });
        });
    });





  $(document).ready(function () {

      //Delete Post
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

      // Function to load comments for a post
      function loadComments(postId) {
          $.ajax({
              url: '/posts/' + postId + '/comments',
              type: 'GET',
              success: function(response) {
                  var comments = response.comments;
                  var commentsHtml = '';
                  if (comments.length > 0) {
                      commentsHtml += '<ul>';
                      comments.forEach(function(comment) {
                          commentsHtml += '<li>' + comment.comment + '</li><br>';
                      });
                      commentsHtml += '</ul>';
                  } else {
                      commentsHtml = 'No comments available.';
                  }
                  $('#commentsContainer').html(commentsHtml);
                  $('#commentsModal').modal('show');
              },
              error: function(xhr, status, error) {
                  console.error(xhr.responseText);
              }
          });
      }

      // Event listener for clicking on "View" comments link
      $('.view-comments').click(function(e) {
          e.preventDefault();
          var postId = $(this).data('post-id');
          loadComments(postId);
      });

  });
  
</script>

@endsection
