@extends('layout')

@section('title', 'View User Address')

@section('content')

<br>
<div class="container mt-3">
  <div>
    <a href="/" >Back</a>
  </div>
  <br>

@foreach ($addresses as $address)
    <p><li>{{ $address->address }}</li></p>
@endforeach

</div>

@endsection