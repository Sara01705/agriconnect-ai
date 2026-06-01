@extends('layouts.app')

@section('content')

<h3 class="mb-4">All Buy Requests</h3>

<table class="table table-bordered table-striped text-center align-middle">

    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Product</th>
            <th>Farmer</th>
            <th>Buyer Name</th>
            <th>Buyer Phone</th>
            <th>Status</th>
            <th>Date</th>
        </tr>
    </thead>

    <tbody>

    @forelse($requests as $req)
        <tr>
            <td>{{ $req->id }}</td>
            <td>{{ $req->product->product_name }}</td>
            <td>{{ $req->product->farmer->name }}</td>
            <td>{{ $req->buyer_name }}</td>
            <td>{{ $req->buyer_phone }}</td>

            <td>
                @if($req->status == 'pending')
                    <span class="badge bg-warning">Pending</span>
                @elseif($req->status == 'accepted')
                    <span class="badge bg-success">Accepted</span>
                @else
                    <span class="badge bg-danger">Rejected</span>
                @endif
            </td>

            <td>{{ $req->created_at->format('d M Y') }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="7" class="text-muted">No requests found</td>
        </tr>
    @endforelse

    </tbody>

</table>

@endsection