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

          <!-- Search Results Section -->
          <div class="row mt-3">
              <div class="col-md-12">

            <!-- Check datatable -->
        <div class="container mt-2">
            <div class="row mb-2">
                <div class="col-md-4">
                  <!-- Author Filter -->
                  <div class="form-group">
                      <label for="authorName">Author:</label>
                      <select id="authorName" name="authorName" class="form-control">
                          <option value="all">All Authors</option>
                          @foreach($userNames as $authorId => $authorName)
                              <option value="{{ $authorName->id }}" {{ request('author') == $authorName->id ? 'selected' : 'all' }}>{{ $authorName->name }}</option>
                          @endforeach
                      </select>
                  </div>
                </div>
                <div class="col-md-4">
                    <!-- Status Filter -->
                    <div class="form-group">
                        <label for="postStatus">Status:</label>
                        <select id="postStatus" name="postStatus" class="form-control">
                            <option value="all">All Statuses</option>
                            <option value="1" {{ request('status') == "1" ? 'selected' : 'all' }} >Active</option>
                            <option value="0" {{ request('status') == "0" ? 'selected' : 'all' }}>Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <!-- Search Input Count -->
                    <div class="form-group">
                        <label for="commentsCountPosts">Comments count:</label>
                        <input type="text" id="commentsCountPosts" name="commentsCountPosts" class="form-control" onkeypress="return /[0-9]/i.test(event.key)" placeholder="Count" value="{{ request('commentsCount') }}">
                    </div>
                </div>
            </div>

                    <table id="posts-table" class="table table-hover" width="1200px">
                    <thead class="table-success">
                        <tr>
                            <th>Sl no</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Date Published</th>
                            <th>Comments Count</th>
                            <th>Status</th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    </table>
              </div>
          </div>
        </div>
      </div>

    </form>

    <br>
    <table class="table table-hover">

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
    <br>
    <br>

</div>

<script type="text/javascript">
    
    $(document).ready(function() {
        $('#posts-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('posts.index') }}",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'title', name: 'title' },
                { data: 'user_id', name: 'user' },
                { data: 'date_published', name: 'datePublished' },
                { data: 'comments_count', name: 'commentsCount',
                    render: function(data, type, row) {
                        return '<a href="#" class="view-comments-page" data-post-id="' + row.id + '">' + data + '</a>';
                    }
                },
                { data: 'is_active', name: 'status' },
                {
                    data: 'id',
                    name: 'view',
                    render: function(data, type, row) {
                            return '<a href="{{ route("posts.show", ["post" => ":postId"]) }}" id="view-user" class="btn btn-primary"> View </a>'.replace(':postId', row.id);
                    }
                },
                {
                    data: 'id',
                    name: 'edit',
                    render: function(data, type, row) {
                            return '<a href="{{ route("posts.edit", ["post" => ":postId"]) }}" id="edit-user" class="btn btn-secondary"> Edit </a>'.replace(':postId', row.id);
                    }
                },
                {
                    data: 'id',
                    name: 'delete',
                    render: function(data, type, row) {
                        
                            return '<a href="javascript:void(0)" id="delete-post" data-url="{{ route("posts.destroy", ["post" => ":postId"]) }}" class="btn btn-danger"> Delete </a>'.replace(':postId', row.id);
                    }
                }
            ],
            rowCallback: function(row, data, index) {
                $('td:eq(0)', row).html(index + 1);
            }
        });

        $('#posts-table').on('click', '.view-comments-page', function(e) {
            e.preventDefault();
            var postId = $(this).data('post-id');

            // Comments view with postId
            loadComments(postId);
        });

        //Filter for author name
        $('#authorName').on('keyup change', function() {
            var authorId = $(this).val();

            $('#posts-table').DataTable().ajax.url("{{ route('posts.index') }}?author=" + authorId).load();
        });

        //Filter for status
        $('#postStatus').on('keyup change', function() {
            var statusId = $(this).val();

            $('#posts-table').DataTable().ajax.url("{{ route('posts.index') }}?status=" + statusId).load();
        });

        //Filter for comments count
        $('#commentsCountPosts').on('keyup change', function() {
            var commentsCount = $(this).val();

            $('#posts-table').DataTable().ajax.url("{{ route('posts.index') }}?commentsCount=" + commentsCount).load();
        });

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

      // Function to load comments for a post need to remove
      function loadCommentsOld(postId) {
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

      // Event listener for clicking on "View" comments link need to remove
      $('.view-comments').click(function(e) {
          e.preventDefault();
          var postId = $(this).data('post-id');
          loadCommentsOld(postId);
      });

  });
  
</script>

@endsection
