@extends('layouts.app')

@section('content')

<h3 class="mb-4">All Farmers</h3>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif





<table class="table table-bordered table-hover table-striped">

<thead class="table-dark">
<tr>
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Phone</th>
<th>District</th>
<th>State</th>
<th>Total Revenue</th>
<th>Action</th>
<th>Status</th>
<th>Availability</th>
</tr>
</thead>

<tbody>

@forelse($farmers as $farmer)

<tr>

<td>{{ $farmer->id }}</td>

<td>

<strong>{{ ucfirst($farmer->name) }}</strong>

@if(($farmer->revenue ?? 0) > 3000)
<br>
<span class="badge bg-danger mt-1">🔥 High Seller</span>

@elseif(($farmer->revenue ?? 0) > 750)
<br>
<span class="badge bg-warning text-dark mt-1">⭐ Top Farmer</span>

@endif

</td>

<td>{{ $farmer->email }}</td>

<td>{{ $farmer->phone }}</td>

<td>{{ ucfirst($farmer->district) }}</td>
<td>{{ ucfirst($farmer->state) }}</td>

<td>₹ {{ number_format($farmer->revenue ?? 0,2) }}</td>

<td>
{{-- ACTION --}}

@if(!$farmer->verified)
    <a href="/admin/verify-farmer/{{ $farmer->id }}" class="btn btn-success btn-sm">
        Verify
    </a>
@else
    <span class="badge bg-success">✔ Verified</span>
@endif
@if(!$farmer->is_blocked)

<a href="{{ route('admin.farmer.block',$farmer->id) }}"
class="btn btn-danger btn-sm">
Block
</a>

@else

<a href="{{ route('admin.farmer.unblock',$farmer->id) }}"
class="btn btn-success btn-sm">
Unblock
</a>

@endif

</td>
{{-- STATUS --}}
<td>

@if($farmer->is_blocked == 1)
<span class="badge bg-danger">Blocked</span>
@else
<span class="badge bg-success">Active</span>
@endif

</td>
{{-- AVAILABILITY --}}
<td class="text-center">

    <!-- 🔥 AVAILABILITY BADGE -->
    <span class="badge px-3 py-2 rounded-pill shadow-sm
        {{ $farmer->availability == 'available' ? 'bg-success' : 
           ($farmer->availability == 'busy' ? 'bg-warning text-dark' : 'bg-danger') }}">
        
        {{ $farmer->availability == 'available' ? '🟢 Available' : 
           ($farmer->availability == 'busy' ? '🟡 Busy' : '🔴 Offline') }}
    </span>

    <!-- 🔽 FORM -->
    <form action="/admin/update-availability/{{ $farmer->id }}" method="POST" class="mt-2">
        @csrf

        <select name="availability" class="form-select form-select-sm mb-2 text-center">
            <option value="available" {{ $farmer->availability == 'available' ? 'selected' : '' }}>Available</option>
            <option value="busy" {{ $farmer->availability == 'busy' ? 'selected' : '' }}>Busy</option>
            <option value="offline" {{ $farmer->availability == 'offline' ? 'selected' : '' }}>Offline</option>
        </select>

        <!-- 🔥 BUTTON -->
        <button type="submit" class="btn btn-primary btn-sm px-3 py-1"
            style="background: linear-gradient(45deg, #4facfe, #00f2fe); border:none; color:white;">
            Update
        </button>

    </form>

</td>

</td>

 
</tr>

@empty

<tr>
<td colspan="8" class="text-center text-muted">
No farmers found
</td>
</tr>

@endforelse

</tbody>

</table>

@endsection