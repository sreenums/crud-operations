<!DOCTYPE html>
<html lang="en">
<head>
  <title>Add User</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<br>
<div class="container mt-3">
  <a href="/" class="btn btn-dark">Back</a>
  @if (session('success'))
  <p>
    <div class="alert alert-success">
      {{ session('success') }}
    </div>
  </p>
  @endif
  
  <h2>Add User</h2>
  <form id="registrationForm" action="/submit-registration" method="POST">
    @csrf
    <div class="form-row">
      <div class="form-group col-md-6">
        <label for="name">Name</label>
        <input type="text" class="form-control" id="inputName" name="inputName" placeholder="Name">
      </div>
    </div>
    <div class="form-group col-md-6">
      <label for="address1">Address</label>
      <input type="text" class="form-control" id="inputAddress1" name="inputAddress1" placeholder="1234 Main St">
    </div>
    <div class="form-group col-md-6">
      <label for="address2">Address 2</label>
      <input type="text" class="form-control" id="inputAddress2" name="inputAddress2" placeholder="Apartment, studio, or floor">
    </div>
    <div class="form-row">
      <div class="form-group col-md-2">
        <label for="contactNo">Contact No</label>
        <input type="text" class="form-control" id="contactNo" name="contactNo" placeholder="+91 ">
      </div>
    </div>
    <br>
    <button type="submit" class="btn btn-primary">Register User</button>
  </form>
</div>

</body>
</html>