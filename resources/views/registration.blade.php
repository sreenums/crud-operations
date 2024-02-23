@extends('layout')

@section('title', 'Add User')

@section('content')

<br>
<div class="container mt-3">
  <div>
    <a href="/home" class="btn btn-dark">Back</a>
  </div>
  <br>

  @if (session('success'))
    <p>
      <div class="alert alert-success">
        {{ session('success') }}
      </div>
    </p>
  @endif
  
  <h2>Add New User</h2>

  <form method="POST" id="registrationForm" class="was-validated" action="{{ route('users.store') }}" >
    @csrf

    <div class="mb-3 mt-3 col-md-6">
      <label for="inputName" class="form-label">Name:</label>
      <input type="text" class="form-control" id="inputName" placeholder="Enter name" name="inputName" required>
      @if ($errors->has('inputName'))
        <div class="validation-error">Please fill out this field.</div>
      @endif
    </div>

    <div class="mb-3 col-md-6">
      <label for="emailId" class="form-label">Email Id:</label>
      <input type="email" class="form-control" id="emailId" placeholder="Enter Email Id" name="emailId" required>
      @if ($errors->has('emailId'))
        <div class="validation-error">Please fill out this field.</div>
      @endif
    </div>

    <div class="mb-3 col-md-6">
      <label for="inputAddress1" class="form-label">Address 1:</label>
      <input type="text" class="form-control" id="inputAddress1" placeholder="Enter address" name="inputAddress1" required>
      @if ($errors->has('inputAddress1'))
        <div class="validation-error">Please fill out this field.</div>
      @endif
    </div>

    <div class="mb-3 col-md-6">
      <label for="inputAddress2" class="form-label">Address 2 (optional):</label>
      <input type="text" class="form-control" id="inputAddress2" placeholder="Enter address 2" name="inputAddress2" >
      @if ($errors->has('inputAddress2'))
        <div >Please fill out this field.</div>
      @endif
    </div>

    <div class="mb-3 col-md-6">
      <label for="password" class="form-label">Password:</label>
      <input type="password" class="form-control" id="password" placeholder="Enter Password" minlength="8" name="password" required>
      @if ($errors->has('password'))
        <div >Please fill out this field.</div>
      @endif
    </div>

    <div class="mb-3 col-md-2">
      <label for="inputAddress1" class="form-label">Contact No:</label>
      <input type="text" class="form-control" id="contactNo" placeholder="+91" name="contactNo" maxlength="10" minlength="10" onkeypress="return /[0-9]/i.test(event.key)" required>
      
      @if ($errors->has('contactNo'))
        <div class="validation-error">Please fill out this field.</div>
      @endif
    </div>
    
  <button type="submit" class="btn btn-primary">Submit</button>
  </form>

</div>

@endsection