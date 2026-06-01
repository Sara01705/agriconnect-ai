@extends('layouts.app')

@section('content')

<div class="container mt-4">
    <h3>📦 Order History</h3>

    <table class="table table-bordered mt-3">
        <thead class="table-dark">
            <tr>
                <th>Buyer</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>{{ optional($order->user)->name }}</td>
                    <td>{{ optional($order->product)->product_name }}</td>
                    <td>{{ $order->quantity }}</td>
                    <td>₹ {{ $order->total_price }}</td>
                    <td>
                        <span class="badge bg-{{ 
                            $order->status == 'accepted' ? 'success' : 
                            ($order->status == 'rejected' ? 'danger' : 'warning') 
                        }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection