@extends('layouts.app')

@section('content')

<div class="container mt-4">

<h3 class="mb-4 fw-bold">All Products</h3>

<table class="table table-bordered table-striped text-center align-middle">

<thead class="table-dark">
<tr>
    <th>Name</th>
    <th>Farmer</th>
    <th>Price</th>
    <th style="width:150px;">Action</th>
    <th>Status</th>
</tr>
</thead>

<tbody>

@foreach($products as $product)

<tr>

    {{-- PRODUCT NAME --}}
    <td>{{ ucfirst($product->product_name) }}</td>

    {{-- FARMER NAME --}}
    <td>{{ $product->farmer->name }}</td>

    {{-- PRICE --}}
    <td>₹ {{ $product->price }}</td>

    {{-- ACTION BUTTON --}}
    <td>

        @if($product->status == 'Available')

        <a href="{{ route('admin.product.block',$product->id) }}"
        class="btn btn-warning btn-sm">
        Block
        </a>

        @else

        <a href="{{ route('admin.product.unblock',$product->id) }}"
        class="btn btn-success btn-sm">
        Unblock
        </a>

        @endif

    </td>

    {{-- STATUS COLUMN --}}
    <td>

        @if($product->status == 'Blocked')

        <span class="badge bg-danger">
        Blocked
        </span>

        @elseif($product->quantity == 0)

        <span class="badge bg-secondary">
        Out of Stock
        </span>

        @else

        <span class="badge bg-success">
        Available
        </span>

        @endif

    </td>

</tr>

@endforeach

</tbody>

</table>

</div>

@endsection