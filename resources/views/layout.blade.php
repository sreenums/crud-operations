<!DOCTYPE html>
<html lang="en">
<head>
  <title>
    @yield('title')
  </title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
  
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    .validation-error {
        color: red;
    }

    .max-tb-width{
      max-width: 790px;
    }
  </style>

  @section('optional-section')  
  <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" href="{{ route('dashboard') }}">Home</a>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
          <a class="nav-link" href=" {{ route('posts.index'); }} ">Posts</a>
        </li>
        <li class="nav-item active">
          <a class="nav-link" href=" {{ route('user.home'); }} ">Users</a>
        </li>
      </ul>
    </div>

    <div class="container mt-3 d-flex justify-content-end">
        <a href=" {{ route('logout') }}" class="btn btn-info btn-lg mr-5">
          <span class="glyphicon glyphicon-log-out"></span> Log out
        </a>
    </div>
  </nav>
  <br>
  @show

</head>
<body>
    @yield('content')
</body>
</html>