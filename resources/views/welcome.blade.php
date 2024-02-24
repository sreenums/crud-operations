@extends('layout')

@section('title', 'Home Page')

@section('content')


<div class="container mt-3">
  <div class="text-right">
    <a href="{{ route('users.create') }}" class="btn btn-success">Add New</a>
  </div>
  <br>
  <h2>CRUD Operations</h2>
  <table class="table table-hover">
    <thead class="table-success">
      <tr>
        <th>Sl.No</th>
        <th>Name</th>
        <th>Address</th>
        <th>Contact No</th>
        <th></th>
      </tr>
    </thead>
    <tbody>

        @foreach ($users as $user)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td><a href="{{ route('users.edit', ['user' => $user->id]) }}"> {{ $user->name; }} </a></td>
            <td> {{ $user->address->address; }} </td>
            <td>{{ $user->contact; }}</td>
            <td>
              <a href="javascript:void(0)" id="delete-user" data-url="{{ route('users.destroy', ['user' => $user->id]) }}" class="btn btn-danger"> Delete </a>
            </td>
          </tr>
        @endforeach

    </tbody>
  </table>
</div>

<script type="text/javascript">

  $(document).ready(function () {

      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });

      $('body').on('click', '#delete-user', function () {

        var userURL = $(this).data('url');
        var trObj = $(this);
        if(confirm("Are you sure you want to delete this user?") == true){

              $.ajax({
                  url: userURL,
                  type: 'DELETE',
                  dataType: 'json',

                  success: function(data) {
                      alert(data.success);
                      trObj.parents("tr").remove();
                  }
              });
        }
     });

  });
</script>

@endsection