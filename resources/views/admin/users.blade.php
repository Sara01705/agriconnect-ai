@extends('layouts.app')

@section('content')

<div class="container mt-4">

<h3>👤 Users List</h3>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<!-- SEARCH FORM -->
<form method="GET" action="{{ route('admin.users') }}" class="mb-3 d-flex">

    <input type="text" name="search" 
           value="{{ $search ?? '' }}" 
           class="form-control me-2" 
           placeholder="Search by name, email, or phone">

    <button type="submit" class="btn btn-primary">Search</button>

    <a href="{{ route('admin.users') }}" class="btn btn-secondary ms-2">Reset</a>

</form>

<!-- TABLE -->
<table class="table table-bordered mt-3">
<thead class="table-dark">
<tr>
<th>Name</th>
<th>Email</th>
<th>Phone</th>
<th>Actions</th>
</tr>
</thead>

<tbody>
@forelse($users as $user)
<tr>
<td>{{ $user->name }}</td>
<td>{{ $user->email }}</td>
<td>{{ $user->phone }}</td>
<td>
    <a href="{{ route('admin.user.edit', $user->id) }}" class="btn btn-sm btn-warning">
        Edit
    </a>

    <form action="{{ route('admin.user.delete', $user->id) }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button class="btn btn-sm btn-danger"
        onclick="return confirm('Delete this user?')">
        Delete
        </button>
    </form>
</td>
</tr>

@empty
<tr>
<td colspan="4" class="text-center text-danger">
    No users found
</td>
</tr>
@endforelse

</tbody>
</table>

</div>

@endsection