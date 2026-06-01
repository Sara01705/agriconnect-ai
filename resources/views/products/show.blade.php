@extends('layouts.app')

@section('content')
<style>
.badge-soft-success{
    background:#dcfce7;
    color:#166534;
}

.badge-soft-warning{
    background:#fef3c7;
    color:#92400e;
}

.badge-soft-danger{
    background:#fee2e2;
    color:#991b1b;
}
.status-badge {
    font-size: 13px;
    padding: 5px 12px;
    border-radius: 20px;
    font-weight: 500;
}

.status-wrapper {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
}

/* Dot */
.status-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
}

/* Colors */
.available {
    animation: pulse 1.5s infinite;
}

@keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(34,197,94,0.7); }
    70% { box-shadow: 0 0 0 8px rgba(34,197,94,0); }
    100% { box-shadow: 0 0 0 0 rgba(34,197,94,0); }
}

/* Text */
.available-text { color: #166534; }
.busy-text { color: #92400e; }
.offline-text { color: #991b1b; }
.card {
    border-radius:16px;}
.btn-outline-success {
    border-width: 2px;
}

hr {
    margin-top: 40px;
    margin-bottom: 30px;
}

.btn {
    border-radius:10px;
}
.btn-primary {
    background: linear-gradient(135deg, #2563eb, #3b82f6);
    border: none;
}
.card:hover {
    transform: translateY(-5px);
    transition: 0.3s;
}
.verified-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 18px;
    height: 18px;
    background: #3b82f6;
    color: white;
    font-size: 11px;
    border-radius: 50%;
    border: 2px solid white;
    box-shadow: 0 2px 6px rgba(59,130,246,0.4);
}

.verified-badge:hover {
    transform: scale(1.1);
    transition: 0.2s;
}
</style>
<div class="container mt-5" style="max-width:1200px;">

<div class="card shadow-sm p-4">

<div class="row align-items-center">

    <!-- Product Image -->
    <div class="col-md-5 text-center">
        <img src="{{ asset('storage/'.$product->image) }}"
     class="img-fluid rounded"
     style="width:100%; height:320px; object-fit:cover;">
    </div>

    <!-- Product Details -->
    <div class="col-md-7">

        <h3 class="fw-bold">{{ $product->name }}</h3>

        <p class="text-muted">
            Category: {{ $product->category }}
        </p>

        <h2 class="text-success fw-bold">
            ₹ {{ number_format($product->price,2) }} / {{ $product->unit }}
        </h4>

        <p>
            <strong>Available Quantity:</strong>
            {{ $product->quantity }} {{ $product->unit }}
        </p>

        <p class="d-flex align-items-center gap-2 mb-1">
    <strong>Farmer:</strong> 
    {{ $product->farmer->name }}

    @if($product->farmer->verified)
        <span class="verified-badge">✓</span>
    @endif
</p>
</p>
     
  <div class="status-wrapper mt-2">
    @if($product->farmer->availability == 'available')
        <span class="status-dot available"></span>
        <span class="status-text available-text">Available</span>
    @elseif($product->farmer->availability == 'busy')
        <span class="status-dot busy"></span>
        <span class="status-text busy-text">Busy</span>
    @else
        <span class="status-dot offline"></span>
        <span class="status-text offline-text">Offline</span>
    @endif
</div>
</p>

        <div class="mt-3">

 <a href="{{ route('buy.request', $product->id) }}" 
       class="btn btn-primary fw-bold">
        🛒 Request to Buy
    </a>

            <a href="tel:{{ $product->farmer->phone }}" class="btn btn-outline-success">
                📞 Call Farmer
            </a>

            <a href="https://wa.me/{{ $product->farmer->phone }}" class="btn btn-outline-success">
                WhatsApp
            </a>

        </div>

    </div>

</div>

</div>


{{-- Customers Also Bought Section --}}

@if($alsoBought->count() > 0)

<hr class="mt-5">

<h4 class="text-success fw-bold mb-3">
 Frequently Bought Together
</h4>

<div class="row g-3">

@foreach($alsoBought as $item)

<div class="col-md-3">

    <div class="card h-100 shadow-sm text-center">

        <img src="{{ asset('storage/'.$item->image) }}"
     class="card-img-top"
     style="height:200px; object-fit:cover;">

        <div class="card-body">

            <h6 class="fw-bold">
                {{ $item->name }}
            </h6>

            <p class="text-success">
                ₹ {{ number_format($item->price,2) }} / {{ $item->unit }}
            </p>

            <a href="{{ route('products.show',$item->id) }}"
               class="btn btn-outline-success btn-sm">
               View
            </a>

        </div>

    </div>

</div>

@endforeach

</div>

@endif

</div>

@endsection