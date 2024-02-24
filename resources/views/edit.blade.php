@extends('layout')

@section('title', 'Edit User')

@section('content')

<br>
<div class="container mt-3 max-tb-width">
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

  <div>
    <h2>Edit User</h2>
  </div>
  <form id="editSave" action="{{ route('users.update', ['user' => $user->id] ) }}" class="was-validated" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3 mt-3 col-md-10">
      <label for="inputName" class="form-label">Name:</label>
      <input type="text" class="form-control" id="inputName" placeholder="Enter name" name="inputName" maxlength="255" value="{{ $user->name }}" required>
      @if ($errors->has('inputName'))
        <div class="validation-error">Field can't be empty. Please fill out this field.</div>
      @endif
    </div>
    
    <div class="mb-3 col-md-10">
      <label for="inputAddress1" class="form-label">Existing Address:</label>
      <input type="text" class="form-control" id="inputAddress" placeholder="Enter address" name="inputAddress" maxlength="255" value="{{ $user->address->address }}" required>
      
      @if ($errors->has('inputAddress'))
        <div class="validation-error">Field can't be empty. Please fill out this field.</div>
      @endif
      <div>
        <a href="/view-address/{{$user->id}}">View Existing addresses</a>
      </div>
    </div>

    <div class="mb-3 col-md-10">
      <label for="emailId" class="form-label">Email Id</label>
      <input type="text" class="form-control border border-light" id="emailId"name="emailId" value="{{ $user->email }}" disabled>
    </div>

    <div class="mb-3 col-md-10">
      <label for="inputAddress1" class="form-label">Add New Address (optional)</label>
      <input type="text" class="form-control border border-light" id="newAddress" placeholder="Enter address 2" name="newAddress" >
      @if ($errors->has('newAddress'))
        <div >Please fill out this field.</div>
      @endif
    </div>

    <div class="mb-3 col-md-3">
      <label for="inputAddress1" class="form-label">Contact No:</label>
      <input type="text" class="form-control" id="contactNo" placeholder="+91" name="contactNo" maxlength="10" minlength="10" onkeypress="return /[0-9]/i.test(event.key)" value="{{ $user->contact }}" required>
      @if ($errors->has('contactNo'))
        <div class="validation-error">Field can't be empty. Please fill out this field.</div>
      @endif
    </div>
    
    <button type="submit" class="btn btn-primary">Modify User</button>
  </form>
</div>

@endsection