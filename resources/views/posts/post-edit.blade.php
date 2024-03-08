@extends('layout')

@section('title', 'Edit Post')

@section('content')
<br>
<div class="container mt-3 pl-5 max-tb-width">
  <br>
  <div class="mt-3">
    <a href="{{ route('posts.index') }}" class="btn btn-dark">Back</a>
  </div>
  <br>

  @if (session('success'))
    <p>
      <div class="alert alert-success">
        {{ session('success') }}
      </div>
    </p>
  @endif
  
  <h2>Edit Post</h2>

  <form method="POST" id="editForm" class="was-validated" action="{{ route('posts.update', ['post'=> $post->id] ) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div class="col-md-8">
      <label for="title" class="form-label">Title</label>
      <input type="text" class="form-control" id="title" name="title" maxlength="250" value="{{ $post->title }}" required>
    </div>
    <div class="col-md-6 mt-3">
      <label for="author" class="form-label">Author</label>
      <select id="author" name="author" class="form-select" required>
        <option selected value="">--Select-- </option>

        @foreach ($users as $user)
        <option value="{{ $user->id }}" @if ($user->id == $post->user_id)
            selected
        @endif >{{ $user->name }} </option>
        @endforeach

      </select>
    </div>
    <div class="col-12 mt-3">
      <label for="content" class="form-label">Content</label>
      <textarea class="form-control" id="content" name="content" rows="6" maxlength="1000" required>{{ $post->content }}</textarea>
    </div>
    <div class="col-md-4 mt-3">
      <label for="datePublished" class="form-label">Date published</label>
      <input type="date" id="datePublished" class="form-control" name="datePublished" value="{{ \Carbon\Carbon::parse($post->date_published)->format('Y-m-d') }}" required>
    </div>
    <div class="col-md-6 mt-3">
      <label for="image" class="form-label">Update new image</label>
      <input type="file" name="image" id="image" class="form-control" accept="image/*">
    </div>
    <div class="col-md-3 mt-3">
      <label for="checkActive" class="form-label">Status</label>
      <select id="checkActive" name="checkActive" class="form-select" required>
          <option value="1" {{ $post->is_active == 1 ? 'selected' : '' }}>Active</option>
          <option value="0" {{ $post->is_active == 0 ? 'selected' : '' }}>Inactive</option>
      </select>
    </div>
    
    @php
      // $arrayData = json_decode($post->categories, true);
      // $categoryIds = array_map(function($item) {
      //                   return $item['category_master_id'];
      //               }, $arrayData);
      $categoryIds = array_column(json_decode($post->categories, true), 'category_master_id');
    @endphp
    @if(isset($categories) && $categories != '[]')
    <div class="col-md-6 mt-3">
      Categories
      <div class="row">
          @foreach ($categories as $category)
          <div class="col">
              <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="{{ $category->id }}" id="{{ $category->id }}" name="categories[]" {{ in_array($category->id,$categoryIds) ? 'checked' : '' }}>
                  <label class="form-check-label" for="{{ $category->id }}">{{ $category->category }}</label>
              </div>
          </div>
          @endforeach
      </div>
    </div>
    @endif

    <div class="col-12 mt-3">
      <button type="submit" class="btn btn-primary">Submit</button>
    </div>
  </form>

</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('#checkActive').on('change', function() {
            var selectedValue = $(this).val();
            // Do something with the selected value, if needed
            console.log(selectedValue);
        });
    });
</script>

@endsection