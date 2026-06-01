@extends('layouts.app')

@section('content')

<h3>My Buy Requests</h3>
<h3>Incoming Buy Requests</h3>

@if(session('whatsapp_link'))

<div class="alert alert-success">

Request accepted successfully.

<br><br>

<a href="{{ session('whatsapp_link') }}" target="_blank" class="btn btn-success">
Send WhatsApp Message
</a>

</div>

@endif
<table class="table table-bordered text-center">
    <thead class="table-dark">
        <tr>
            <th>Product</th>
            <th>Farmer</th>
            <th>Quantity</th>
            <th>Total Price</th>
            <th>Status</th>
        </tr>
    </thead>

    <tbody>
        @forelse($requests as $req)
        <tr>
            <td>{{ $req->product->product_name }}</td>
            <td>{{ $req->product->farmer->name }}</td>
            <td>{{ $req->quantity }}</td>
            <td>₹ {{ $req->total_price }}</td>
            <td>
                @if($req->status == 'pending')
                    <span class="badge bg-warning">Pending</span>
                @elseif($req->status == 'accepted')
                    <span class="badge bg-success">Accepted</span>
                @else
                    <span class="badge bg-danger">Rejected</span>
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5">No requests yet</td>
        </tr>
        @endforelse
    </tbody>
</table>

@endsection