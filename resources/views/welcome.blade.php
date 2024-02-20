<!DOCTYPE html>
<html lang="en">
<head>
  <title>Crud Operations</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<br>
<div class="container mt-3">
    <a href="/addnew" class="btn btn-success float-right">Add New</a>
  <h2>CRUD Operations</h2>
  <table class="table table-hover">
    <thead class="table-success">
      <tr>
        <th>Sl.No</th>
        <th>Name</th>
        <th>Address</th>
        <th>Contact No</th>
      </tr>
    </thead>
    <tbody>

        @foreach ($users as $user)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td><a href="/edit/{{ $user->id }}"> {{ $user->name; }} </a></td>
            <td>Test Address</td>
            <td>{{ $user->contact; }}</td>
          </tr>
        @endforeach

    </tbody>
  </table>
</div>

</body>
</html>