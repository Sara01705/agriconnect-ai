@extends('layouts.app')

@section('content')

<style>
    /* ── Dashboard Header ── */
    .dashboard-header {
        background: linear-gradient(135deg, #1e7e34 0%, #28a745 60%, #52c96e 100%);
        border-radius: 20px;
        padding: 30px 40px;
        color: white;
        position: relative;
        overflow: hidden;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(40, 167, 69, 0.15);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 20px;
    }

    .dashboard-header::before {
        content: '';
        position: absolute;
        top: -40px; right: -40px;
        width: 180px; height: 180px;
        border-radius: 50%;
        background: rgba(255,255,255,0.08);
    }

    .dashboard-header::after {
        content: '';
        position: absolute;
        bottom: -60px; left: -20px;
        width: 220px; height: 220px;
        border-radius: 50%;
        background: rgba(255,255,255,0.05);
    }

    .dashboard-header-text {
        position: relative;
        z-index: 1;
    }

    .dashboard-header h3 {
        font-weight: 700;
        margin-bottom: 5px;
    }

    .dashboard-header p {
        opacity: 0.9;
        margin-bottom: 0;
    }

    .dashboard-actions {
        position: relative;
        z-index: 1;
        display: flex;
        gap: 12px;
    }

    /* ── Stats Cards ── */
    .stat-card {
        border: none;
        border-radius: 16px;
        padding: 24px;
        display: flex;
        flex-direction: column;
        position: relative;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        color: white;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 25px rgba(0,0,0,0.1);
    }

    .stat-icon {
        position: absolute;
        right: -10px;
        bottom: -15px;
        font-size: 5rem;
        opacity: 0.15;
        transform: rotate(-10deg);
        transition: transform 0.3s ease;
    }

    .stat-card:hover .stat-icon {
        transform: rotate(0deg) scale(1.1);
    }

    .stat-title {
        font-size: 0.95rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        opacity: 0.9;
        margin-bottom: 8px;
    }

    .stat-value {
        font-size: 2.2rem;
        font-weight: 700;
        margin-bottom: 0;
    }

    /* Card Gradients */
    .bg-gradient-products { background: linear-gradient(135deg, #2b5876 0%, #4e4376 100%); }
    .bg-gradient-requests { background: linear-gradient(135deg, #005c97 0%, #363795 100%); }
    .bg-gradient-pending { background: linear-gradient(135deg, #f7971e 0%, #ffd200 100%); color: #333; }
    .bg-gradient-accepted { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
    .bg-gradient-rejected { background: linear-gradient(135deg, #cb2d3e 0%, #ef473a 100%); }
    .bg-gradient-revenue { background: linear-gradient(135deg, #1f4037 0%, #99f2c8 100%); color: #1e5c2a; }
    .bg-gradient-top { background: linear-gradient(135deg, #4b6cb7 0%, #182848 100%); }

    /* Fix pending text color since bg is bright */
    .bg-gradient-pending .stat-title,
    .bg-gradient-pending .stat-value {
        color: #5a3c00;
    }

    /* ── Table Styling ── */
    .recent-orders-card {
        background: white;
        border-radius: 20px;
        padding: 25px 30px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        margin-top: 20px;
    }

    .recent-orders-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .recent-orders-header h4 {
        font-weight: 700;
        color: #2d4a35;
        margin: 0;
    }

    .table-custom {
        margin-bottom: 0;
    }

    .table-custom thead th {
        background: #f1fdf4;
        color: #1e7e34;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.8px;
        border-bottom: 2px solid #d0e8d5;
        padding: 12px 15px;
    }

    .table-custom tbody td {
        padding: 16px 15px;
        vertical-align: middle;
        color: #444;
        border-bottom: 1px solid #edf2ef;
    }

    .table-custom tbody tr {
        transition: background-color 0.2s;
    }

    .table-custom tbody tr:hover {
        background-color: #f8fcf9;
    }

    /* ── Action Buttons ── */
    .btn-action {
        border-radius: 10px;
        padding: 10px 20px;
        font-weight: 600;
        transition: all 0.2s;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.15);
    }
    
    .btn-add { background: white; color: #1e7e34; }
    .btn-add:hover { background: #f0fdf4; color: #1e7e34; }

</style>

{{-- Dashboard Header --}}
<div class="dashboard-header">
    <div class="dashboard-header-text">
        <h3>👋 Welcome to your Dashboard</h3>
        <p>Manage your products, track orders, and view your revenue.</p>
    </div>
    <div class="dashboard-actions">
        <a href="{{ route('products.create') }}" class="btn btn-action btn-add">
            <i class="bi bi-plus-circle"></i> Add New Product
        </a>
    </div>
</div>

{{-- Stats Row 1 --}}
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-lg-4 col-md-6">
        <div class="stat-card bg-gradient-products">
            <div class="stat-title">TOTAL PRODUCTS</div>
            <div class="stat-value">{{ $totalProducts }}</div>
            <div class="stat-icon">📦</div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-4 col-md-6">
        <div class="stat-card bg-gradient-requests">
            <div class="stat-title">TOTAL REQUESTS</div>
            <div class="stat-value">{{ $totalRequests }}</div>
            <div class="stat-icon">📊</div>
        </div>
    </div>

    <div class="col-xl-3 col-lg-4 col-md-6">
        <div class="stat-card bg-gradient-revenue">
            <div class="stat-title">TOTAL REVENUE</div>
            <div class="stat-value">₹{{ number_format($totalRevenue, 2) }}</div>
            <div class="stat-icon">💰</div>
        </div>
    </div>

    @if($topProduct)
    <div class="col-xl-3 col-lg-4 col-md-6">
        <div class="stat-card bg-gradient-top">
            <div class="stat-title">TOP PRODUCT</div>
            <div class="stat-value" style="font-size: 1.5rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                {{ $topProduct->product->product_name }}
            </div>
            <div style="font-size: 0.85rem; margin-top: 5px; opacity: 0.9;">
                Sold: <strong>{{ $topProduct->total_sold }}</strong> units
            </div>
            <div class="stat-icon">🏆</div>
        </div>
    </div>
    @endif
</div>

{{-- AI Market Pulse Widget --}}
<div class="mb-4" style="background: linear-gradient(to right, #f0fdf4, #ffffff); border: 1px solid rgba(40, 167, 69, 0.2); border-radius: 1.25rem; padding: 1.25rem 1.5rem; display: flex; align-items: center; gap: 1.25rem; box-shadow: 0 4px 15px rgba(40, 167, 69, 0.05);">
    <div style="background: linear-gradient(135deg, #28a745, #20c997); width: 3.5rem; height: 3.5rem; min-width: 3.5rem; border-radius: 1rem; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; box-shadow: 0 8px 15px rgba(40, 167, 69, 0.2); color: white;">
        <i class="bi bi-graph-up-arrow"></i>
    </div>
    <div>
        <h5 style="font-weight: 700; color: #1e7e34; margin-bottom: 0.25rem; font-size: 1.1rem;">AI Market Pulse</h5>
        <p style="color: #2d4a35; font-size: 0.95rem; margin: 0;">
            <strong class="text-success">Trending:</strong> Tomato and Onion demand is surging in your region. The AI predicts a <strong>+12%</strong> price increase next week. Consider holding stock if you have storage capacity.
        </p>
    </div>
</div>

{{-- Stats Row 2 --}}
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="stat-card bg-gradient-pending">
            <div class="stat-title">PENDING</div>
            <div class="stat-value">{{ $pendingRequests }}</div>
            <div class="stat-icon">⏳</div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="stat-card bg-gradient-accepted">
            <div class="stat-title">ACCEPTED</div>
            <div class="stat-value">{{ $acceptedRequests }}</div>
            <div class="stat-icon">✅</div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="stat-card bg-gradient-rejected">
            <div class="stat-title">REJECTED</div>
            <div class="stat-value">{{ $rejectedRequests }}</div>
            <div class="stat-icon">❌</div>
        </div>
    </div>
</div>

{{-- Stock Stats Row --}}
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="stat-card" style="background: linear-gradient(135deg, #16a085 0%, #f4d03f 100%); color: #111;">
            <div class="stat-title">TOTAL STOCK QUANTITY</div>
            <div class="stat-value">{{ $totalStockQuantity }}</div>
            <div class="stat-icon">📦</div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="stat-card" style="background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);">
            <div class="stat-title">LOW STOCK PRODUCTS</div>
            <div class="stat-value">{{ $lowStockCount }}</div>
            <div class="stat-icon">⚠️</div>
        </div>
    </div>
</div>

{{-- Low Stock Alerts --}}
@if($lowStockCount > 0)
<div class="recent-orders-card border border-danger mb-4">
    <div class="recent-orders-header text-danger">
        <h4><i class="bi bi-exclamation-triangle-fill"></i> Low Stock Alerts</h4>
    </div>
    <div class="table-responsive">
        <table class="table table-custom table-hover">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Current Stock</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lowStockProducts as $lowStock)
                <tr>
                    <td class="fw-bold">{{ $lowStock->product_name }}</td>
                    <td>{{ ucfirst($lowStock->category) }}</td>
                    <td>
                        <span class="badge rounded-pill {{ $lowStock->quantity == 0 ? 'bg-danger' : 'bg-warning text-dark' }} px-3 py-2">
                            {{ $lowStock->quantity }} {{ $lowStock->unit }}
                        </span>
                    </td>
                    <td>
                        @if($lowStock->quantity == 0)
                            <span class="text-danger fw-bold">Out of Stock</span>
                        @else
                            <span class="text-warning fw-bold">Low Stock</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('products.edit', $lowStock->id) }}" class="btn btn-sm btn-outline-primary">Update Stock</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- New Widgets Row: Chart & Weather --}}
<div class="row g-4 mb-4">
    <!-- Chart -->
    <div class="col-lg-8">
        <div class="recent-orders-card h-100 mt-0">
            <div class="recent-orders-header mb-3">
                <h4>📈 Revenue Trend (Last 6 Months)</h4>
            </div>
            <div style="position: relative; height: 280px; width: 100%;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>
    
    
    
{{-- Quick Links --}}
<div class="d-flex gap-3 mb-4 flex-wrap">
    <a href="{{ route('products.index') }}" class="btn btn-action btn-success">
        <i class="bi bi-box-seam"></i> My Products
    </a>
    <a href="{{ route('farmer.orders') }}" class="btn btn-action btn-secondary">
        <i class="bi bi-clock-history"></i> Order History
    </a>
<a href="{{ route('price.prediction') }}"
   class="btn btn-primary shadow-sm px-4 py-3">
    <i class="bi bi-robot"></i>
    AI Price Predictor
</a>
</div>

{{-- Recent Orders Table --}}
<div class="recent-orders-card">
    <div class="recent-orders-header">
        <h4>🛒 Recent Orders</h4>
    </div>

    <div class="table-responsive">
        <table class="table table-custom table-hover">
            <thead>
                <tr>
                    <th>Buyer</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentOrders as $order)
                <tr>
                    <td class="fw-medium">{{ $order->user->name ?? 'Guest User' }}</td>
                    <td>{{ $order->product->product_name }}</td>
                    <td><span class="badge bg-light text-dark border">{{ $order->quantity }}</span></td>
                    <td class="fw-bold text-success">₹{{ number_format($order->total_price, 2) }}</td>
                    <td>{{ $order->created_at->format('d M, Y') }}</td>
                    <td>
                        <span class="badge rounded-pill px-3 py-2 
                            @if($order->status=='accepted') bg-success 
                            @elseif($order->status=='rejected') bg-danger 
                            @else bg-warning text-dark 
                            @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">
                        <i class="bi bi-inbox fs-2 d-block mb-2 text-black-50"></i>
                        No recent orders found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        
        // Fetch dynamic data from controller
        const months = {!! json_encode($chartMonths) !!};
        const revenues = {!! json_encode($chartRevenues) !!};

        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(40, 167, 69, 0.5)');
        gradient.addColorStop(1, 'rgba(40, 167, 69, 0.0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Revenue (₹)',
                    data: revenues,
                    borderColor: '#28a745',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#28a745',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ' ₹' + context.raw.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    x: { grid: { display: false } },
                    y: { 
                        beginAtZero: true,
                        grid: { borderDash: [5, 5], color: '#e9ecef' },
                        ticks: {
                            callback: function(value) { return '₹' + value; }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
