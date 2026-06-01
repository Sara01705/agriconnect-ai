@extends('layouts.app')

@section('content')

<div class="container mt-4">

<h2 class="mb-4">Incoming Buy Requests</h2>

@if(session('whatsapp_link'))

<div class="alert alert-success">

Request processed successfully.

<br><br>

<a href="{{ session('whatsapp_link') }}" target="_blank" class="btn btn-success">
Send WhatsApp Message
</a>

</div>

@endif


<table class="table table-bordered table-striped text-center">

<thead class="table-dark">
<tr>
<th>Buyer</th>
<th>Product</th>
<th>Quantity</th>
<th>Total Price</th>
<th>Status</th>
<th>Action</th>
</tr>
</thead>

<tbody>

@foreach($requests as $req)

<tr>

<td>
{{ optional($req->user)->name ?? 'User Deleted' }}
</td>

<td>
{{ optional($req->product)->product_name ?? 'Product Removed' }}
</td>

<td>{{ $req->quantity }}</td>

<td>₹ {{ number_format($req->total_price, 2) }}</td>

<td>

@if($req->status == 'pending')
<span class="badge bg-warning">Pending</span>

@elseif($req->status == 'accepted')
<span class="badge bg-success">Accepted</span>

@else
<span class="badge bg-danger">Rejected</span>
@endif

</td>

<td>

@if($req->status == 'pending')

<form action="{{ route('buy.request.accept', $req->id) }}" method="POST" style="display:inline;">
@csrf
<button class="btn btn-success btn-sm">Accept</button>
</form>

<form action="{{ route('buy.request.reject', $req->id) }}" method="POST" style="display:inline;">
@csrf
<button class="btn btn-danger btn-sm">Reject</button>
</form>

@else
<span class="text-muted">No Action</span>
@endif

</td>

</tr>

@endforeach

</tbody>

</table>

</div>

@endsection