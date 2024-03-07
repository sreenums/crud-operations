@extends('layout')

@section('title', 'Add Post')

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
  
  <h2>Add New Post</h2>

  <form method="POST" id="postForm" class="was-validated" action=" {{ route('posts.store') }} " enctype="multipart/form-data">
    @csrf
    <div class="col-md-6">
      <label for="title" class="form-label">Title</label>
      <input type="text" class="form-control" id="title" name="title" maxlength="150" required>
      @if ($errors->has('title'))
        <div class="validation-error">Please fill out this field.</div>
      @endif
    </div>
    <div class="col-md-6">
      <label for="author" class="form-label">Author</label>
      <select id="author" name="author" class="form-select" required>
        <option selected value="">--Select-- </option>

        @foreach ($users as $user)
        <option value="{{ $user->id }} ">{{ $user->name }} </option>
        @endforeach

      </select>
      @if ($errors->has('author'))
        <div class="validation-error">Please select author.</div>
      @endif
    </div>
    <div class="col-12">
      <label for="content" class="form-label">Content</label>
      <textarea class="form-control" id="content" name="content" rows="4" maxlength="1000" required></textarea>
      @if ($errors->has('content'))
        <div class="validation-error">Please enter content.</div>
      @endif
    </div>
    <div class="col-md-4">
      <label for="datePublished" class="form-label">Date published</label>
      <input type="date" id="datePublished" class="form-control" name="datePublished" required>
      @if ($errors->has('datePublished'))
        <div class="validation-error">Please select date</div>
      @endif
    </div>
    <div class="col-md-6">
      <label for="image" class="form-label">Image</label>
      <input type="file" name="image" id="image" class="form-control" accept="image/*">
    </div>
    <div class="col-md-3">
      <label for="checkActive" class="form-label">Status</label>
      <select id="checkActive" name="checkActive" class="form-select">
        <option selected value="1">Active</option>
        <option value="0">Inactive</option>
      </select>
    </div>
    <div class="col-12 mt-3">
      <button type="submit" class="btn btn-primary">Submit</button>
    </div>
  </form>

</div>

@endsection