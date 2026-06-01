@extends('layouts.app')

@section('content')

<style>
/* Header banner */
.orders-banner {
    background: linear-gradient(135deg, #1e7e34 0%, #28a745 60%, #52c96e 100%);
    border-radius: 20px;
    padding: 28px 36px;
    color: white;
    position: relative;
    overflow: hidden;
    margin-bottom: 30px;
    box-shadow: 0 10px 30px rgba(40, 167, 69, 0.12);
}

.orders-banner::before {
    content: '';
    position: absolute;
    top: -40px; right: -40px;
    width: 180px; height: 180px;
    border-radius: 50%;
    background: rgba(255,255,255,0.08);
}

.orders-banner h2 {
    font-weight: 800;
    margin-bottom: 6px;
    letter-spacing: -0.01em;
}

.orders-banner p {
    opacity: 0.9;
    font-size: 0.95rem;
    margin: 0;
}

/* Date filter bar */
.filter-glass-bar {
    background: rgba(255, 255, 255, 0.45);
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    border: 1px solid rgba(255, 255, 255, 0.6);
    border-radius: 1.25rem;
    padding: 1.25rem 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 8px 25px rgba(0,0,0,0.02);
}

/* Order cards */
.order-glass-card {
    border-radius: 1.5rem !important;
    padding: 1.75rem !important;
    margin-bottom: 1.5rem;
    position: relative;
}

.order-product-title {
    font-size: 1.25rem;
    font-weight: 800;
    color: #1e7e34;
    margin-bottom: 0.25rem;
}

.order-grid-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: 1rem;
    margin-top: 1.25rem;
    padding-top: 1rem;
    border-top: 1px solid rgba(40, 167, 69, 0.1);
}

.order-detail-item {
    display: flex;
    flex-direction: column;
}

.order-detail-label {
    font-size: 0.72rem;
    font-weight: 700;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.25rem;
}

.order-detail-value {
    font-size: 0.95rem;
    font-weight: 700;
    color: #1f2937;
}

/* Soft glass status badges */
.status-glass-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.8rem;
    font-weight: 750;
    padding: 0.4rem 1rem;
    border-radius: 2rem;
    letter-spacing: 0.3px;
    text-transform: uppercase;
}

.badge-accepted {
    background: rgba(209, 250, 229, 0.6);
    color: #047857;
    border: 1px solid rgba(52, 211, 153, 0.4);
}

.badge-pending {
    background: rgba(254, 243, 199, 0.6);
    color: #b45309;
    border: 1px solid rgba(251, 191, 36, 0.4);
}

.badge-rejected {
    background: rgba(254, 226, 226, 0.6);
    color: #b91c1c;
    border: 1px solid rgba(248, 113, 113, 0.4);
}

/* Empty State visual */
.empty-state-card {
    background: rgba(255, 255, 255, 0.5);
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    border: 1px solid rgba(255,255,255,0.7);
    border-radius: 1.75rem;
    padding: 4.5rem 2rem;
    text-align: center;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
    margin-top: 1.5rem;
}

.empty-icon-wrap {
    width: 6.5rem;
    height: 6.5rem;
    background: #e8f5ec;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    margin: 0 auto 1.5rem auto;
    box-shadow: inset 0 4px 10px rgba(40, 167, 69, 0.08);
}
</style>

<div class="container mt-4 mb-5 pb-4">
    
    {{-- Header Banner --}}
    <div class="orders-banner d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h2>My Orders</h2>
            <p>Track your purchase requests and active fresh product orders</p>
        </div>
        <div style="font-size: 2.2rem; opacity: 0.9;">🛒</div>
    </div>

    {{-- Date Filter Bar --}}
    <div class="filter-glass-bar">
        <form method="GET" action="{{ url('/my-orders') }}" class="row g-3 align-items-center">
            
            <div class="col-md-4 col-sm-6">
                <label class="glass-label mb-1" for="date">Filter by Date</label>
                <div style="position: relative;">
                    <input type="date" 
                           id="date" 
                           name="date" 
                           class="form-control glass-input w-100" 
                           value="{{ request('date') }}">
                </div>
            </div>

            <div class="col-md-4 col-sm-6 d-flex gap-2 align-self-end mt-sm-4">
                <button type="submit" class="glass-btn-green flex-grow-1" style="padding: 0.65rem 1.25rem !important;">
                    <i class="bi bi-search me-1"></i> Search
                </button>
                <a href="{{ url('/my-orders') }}" class="glass-btn-secondary text-decoration-none d-flex align-items-center justify-content-center" style="padding: 0.65rem 1.25rem !important;">
                    Clear
                </a>
            </div>

        </form>
    </div>

    {{-- Show Selected Filter Chip --}}
    @if(request('date'))
        <div class="alert alert-success d-flex justify-content-between align-items-center border-0 shadow-sm mb-4" style="background: rgba(209, 250, 229, 0.65); border-radius: 1rem; color: #047857;">
            <span>
                <i class="bi bi-funnel-fill me-1"></i> Showing orders submitted on: 
                <strong>{{ \Carbon\Carbon::parse(request('date'))->format('d M Y') }}</strong>
            </span>
            <a href="{{ url('/my-orders') }}" class="text-decoration-none fw-bold" style="color: #b91c1c;">
                Clear Filter ✖
            </a>
        </div>
    @endif

    {{-- Order List or Empty State --}}
    @if($orders->isEmpty())
        <div class="empty-state-card">
            <div class="empty-icon-wrap">🍃</div>
            <h4 class="fw-bold text-dark mb-2">No Orders Found</h4>
            <p class="text-muted mx-auto" style="max-width: 420px; font-size: 0.95rem;">
                @if(request('date'))
                    No purchase requests matches this specific date. Try selecting another date or resetting filters.
                @else
                    You have not placed any orders yet. Explore our fresh marketplace for organic farm items!
                @endif
            </p>
            <div class="mt-4">
                <a href="{{ route('products.index') }}" class="glass-btn-green text-decoration-none">
                    <i class="bi bi-shop me-2"></i> Browse Products
                </a>
            </div>
        </div>
    @else
        <div class="row row-cols-1 g-3">
            @foreach($orders as $order)
                <div class="col">
                    <div class="glass-card order-glass-card">
                        
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                            <div>
                                <h4 class="order-product-title">{{ ucfirst($order->product_name) }}</h4>
                                <span class="text-muted small">
                                    <i class="bi bi-clock me-1"></i> Placed on {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, h:i A') }}
                                </span>
                            </div>

                            <div>
                                @if($order->status == 'accepted')
                                    <span class="status-glass-badge badge-accepted">
                                        <i class="bi bi-patch-check-fill"></i> Accepted
                                    </span>
                                @elseif($order->status == 'pending')
                                    <span class="status-glass-badge badge-pending">
                                        <i class="bi bi-hourglass-split"></i> Pending Approval
                                    </span>
                                @else
                                    <span class="status-glass-badge badge-rejected">
                                        <i class="bi bi-slash-circle"></i> {{ ucfirst($order->status) }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Metadata grid --}}
                        <div class="order-grid-details">
                            <div class="order-detail-item">
                                <span class="order-detail-label">Order Request ID</span>
                                <span class="order-detail-value text-muted">#{{ $order->id }}</span>
                            </div>
                            
                            <div class="order-detail-item">
                                <span class="order-detail-label">Quantity Ordered</span>
                                <span class="order-detail-value">{{ $order->quantity }} Units</span>
                            </div>
                            
                            <div class="order-detail-item">
                                <span class="order-detail-label">Total Amount</span>
                                <span class="order-detail-value text-success" style="font-size: 1.05rem;">₹{{ number_format($order->total_price, 2) }}</span>
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>

@endsection