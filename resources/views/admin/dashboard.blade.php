@extends('layouts.app')

@section('content')

<style>
.card {
    border-radius: 15px;
    padding: 20px;
    color: white;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    transition: 0.3s;
}
.card:hover {
    transform: translateY(-5px);
}
.green { background: linear-gradient(to right,#28a745,#20c997); }
.blue { background: linear-gradient(to right,#007bff,#17a2b8); }
.orange { background: linear-gradient(to right,#fd7e14,#ffc107); }

.action-btns a{
    border-radius: 8px;
    font-weight: 500;
}
</style>

<div class="container mt-4">

<h4>👋 Welcome Admin, Manage your system efficiently</h4>

<div class="row mt-4">

<div class="col-md-4">
<div class="card green">
<h5>👨‍🌾 Total Farmers</h5>
<h2>{{ $farmers }}</h2>
</div>
</div>

<div class="col-md-4">
<div class="card blue">
<h5>👤 Total Users</h5>
<h2>{{ $users }}</h2>
</div>
</div>

<div class="col-md-4">
<div class="card orange">
<h5>📦 Total Products</h5>
<h2>{{ $products }}</h2>
</div>
</div>

</div>

<div class="action-btns mt-4 d-flex gap-3">

<a href="{{ route('admin.farmers') }}" class="btn btn-primary">
👨‍🌾 View Farmers
</a>

<a href="{{ route('admin.users') }}" class="btn btn-info">
👤 View Users
</a>

<a href="{{ route('admin.products') }}" class="btn btn-success">
📦 View Products
</a>

<a href="{{ route('admin.requests') }}" class="btn btn-secondary">
🛒 View Buy Requests
</a>

</div>

<hr>
<h5 class="mt-4">🛒 Recent Orders</h5>

<table class="table table-bordered mt-3">
<thead class="table-dark">
<tr>
    <th>User</th>
    <th>Product</th>
    <th>Quantity</th>
    <th>Total Price</th>
    <th>Status</th>
</tr>
</thead>

<tbody>
@forelse($recentOrders as $order)
<tr>
    <td>{{ $order->user->name ?? 'N/A' }}</td>
    <td>{{ $order->product->product_name ?? 'N/A' }}</td>
    <td>{{ $order->quantity }}</td>
    <td>₹{{ $order->total_price }}</td>
    <td>
        <span class="badge bg-success">
            {{ $order->status ?? 'Pending' }}
        </span>
    </td>
</tr>
@empty
<tr>
    <td colspan="5" class="text-center text-danger">
        No orders found
    </td>
</tr>
@endforelse
</tbody>
</table>


</div>

@endsection