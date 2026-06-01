@extends('layouts.app')

@section('content')

<div class="container mt-4">

<h3>✏️ Edit User</h3>

<form action="{{ route('admin.user.update', $user->id) }}" method="POST">
@csrf

<div class="mb-3">
<label>Name</label>
<input type="text" name="name" value="{{ $user->name }}" class="form-control">
</div>

<div class="mb-3">
<label>Email</label>
<input type="email" name="email" value="{{ $user->email }}" class="form-control">
</div>

<div class="mb-3">
<label>Phone</label>
<input type="text" name="phone" value="{{ $user->phone }}" class="form-control">
</div>

<button class="btn btn-success">Update</button>

<a href="{{ route('admin.users') }}" class="btn btn-secondary">Back</a>

</form>

</div>

@endsection