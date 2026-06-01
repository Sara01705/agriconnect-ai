@extends('layouts.app')

@section('content')

<div class="container mt-4">

    <h3 class="fw-bold text-primary mb-4">
        <i class="bi bi-clock-history"></i> Order History
    </h3>

    {{-- 🔎 Search + Filters --}}
    <form method="GET" class="row g-2 mb-3">

        <div class="col-md-2">
            <input type="text" name="search_user"
                   value="{{ request('search_user') }}"
                   class="form-control"
                   placeholder="Search User">
        </div>

        <div class="col-md-2">
            <input type="text" name="search_product"
                   value="{{ request('search_product') }}"
                   class="form-control"
                   placeholder="Search Product">
        </div>

        <div class="col-md-2">
            <input type="date" name="from_date"
                   value="{{ request('from_date') }}"
                   class="form-control">
        </div>

        <div class="col-md-2">
            <input type="date" name="to_date"
                   value="{{ request('to_date') }}"
                   class="form-control">
        </div>

        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">All Status</option>
                <option value="accepted" {{ request('status')=='accepted' ? 'selected' : '' }}>Accepted</option>
                <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>Pending</option>
                <option value="rejected" {{ request('status')=='rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>

        <div class="col-md-1">
            <button class="btn btn-primary w-100">Filter</button>
        </div>

        <div class="col-md-1">
            <a href="{{ url()->current() }}" class="btn btn-secondary w-100">
                Reset
            </a>
        </div>

    </form>

    {{-- 💰 Earnings Card --}}
    <div class="card mb-3 shadow">
        <div class="card-body bg-success text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Total Earnings</h5>
            <h4 class="mb-0">₹ {{ number_format($totalEarnings, 2) }}</h4>
        </div>
    </div>

    {{-- 📋 Orders Table --}}
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>Buyer</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>

        @forelse($orders as $order)
            <tr>
                <td>{{ optional($order->user)->name ?? 'Guest User' }}</td>
                <td>{{ optional($order->product)->product_name }}</td>
                <td>{{ $order->quantity }}</td>
                <td>₹ {{ number_format($order->total_price, 2) }}</td>
                <td>
                    <span class="badge 
                        @if($order->status == 'accepted') bg-success 
                        @elseif($order->status == 'pending') bg-warning 
                        @else bg-danger 
                        @endif">
                        {{ ucfirst($order->status) }}
                    </span>
                </td>
                <td>{{ $order->created_at->format('d M Y') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center text-muted">
                    No orders found
                </td>
            </tr>
        @endforelse

        </tbody>
    </table>

    {{-- 📄 Pagination --}}
    <div class="d-flex justify-content-end mt-4">
        {{ $orders->onEachSide(1)->withQueryString()->links() }}
    </div>

</div>

@endsection