@extends('layout')

@section('title', 'View User Address')

@section('content')

<br>
<div class="container mt-3 max-tb-width">
  <br>
  <div class="mt-3">
    <a href="/home" >Back</a>
  </div>
  <br>
<div><h2> User Addresses </h2></div>
  
  <br>

@foreach ($addresses as $address)
    <p><li>{{ $address->address }}</li></p>
@endforeach

</div>

@endsection