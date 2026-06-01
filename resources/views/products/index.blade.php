@extends('layouts.app')

@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

body {
    font-family: 'Inter', sans-serif;
    background: linear-gradient(-45deg, #f0fdf4, #f8fafc, #ecfdf5, #ffffff);
    background-size: 400% 400%;
    animation: gradientBG 12s ease infinite;
    color: #1f2937;
}

@keyframes gradientBG {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

/* Subtle Particles */
.particles {
    position: fixed;
    width: 100%;
    height: 100%;
    top: 0; left: 0;
    overflow: hidden;
    z-index: -1;
    pointer-events: none;
}
.particles span {
    position: absolute;
    display: block;
    background: rgba(16, 185, 129, 0.15);
    border-radius: 50%;
    animation: floatUp 20s linear infinite;
    bottom: -150px;
}
.particles span:nth-child(1) { left: 10%; width: 30px; height: 30px; animation-duration: 15s; }
.particles span:nth-child(2) { left: 30%; width: 45px; height: 45px; animation-duration: 22s; animation-delay: 2s; }
.particles span:nth-child(3) { left: 60%; width: 20px; height: 20px; animation-duration: 12s; }
.particles span:nth-child(4) { left: 80%; width: 35px; height: 35px; animation-duration: 18s; animation-delay: 4s; }

@keyframes floatUp {
    0% { transform: translateY(0) scale(0.8); opacity: 0; }
    20% { opacity: 1; }
    80% { opacity: 1; }
    100% { transform: translateY(-120vh) scale(1.2); opacity: 0; }
}

/* Typography & Titles */
.page-title {
    font-size: 2.25rem;
    font-weight: 800;
    color: #1f2937;
    margin-bottom: 2rem;
    letter-spacing: -0.02em;
}

/* Glassmorphism Floating Search Bar */
.search-glass {
    background: rgba(255, 255, 255, 0.65);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border-radius: 2rem;
    padding: 1.25rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    border: 1px solid rgba(255, 255, 255, 0.8);
    transition: all 0.3s ease;
    margin-bottom: 2rem;
}
.search-glass:focus-within {
    box-shadow: 0 15px 40px rgba(16, 185, 129, 0.12);
    border-color: rgba(16, 185, 129, 0.2);
}
.search-glass label {
    display: none;
}
.search-glass .form-control, .search-glass .form-select {
    border-radius: 1rem;
    background: rgba(255, 255, 255, 0.7);
    border: 1px solid #e5e7eb;
    padding: 0.85rem 1.25rem;
    font-weight: 500;
    color: #4b5563;
    transition: all 0.2s;
    height: auto;
}
.search-glass .form-control:focus, .search-glass .form-select:focus {
    box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
    border-color: #34d399;
    outline: none;
}
.search-glass .input-group-text {
    border-radius: 1rem 0 0 1rem;
    background: rgba(255, 255, 255, 0.7);
    border: 1px solid #e5e7eb;
    border-right: none;
    padding-left: 1.25rem;
}
.search-glass .input-group .form-control {
    border-left: none;
    padding-left: 0.5rem;
}

/* Primary Button Antigravity */
.btn-antigravity {
    background: linear-gradient(135deg, #059669, #10b981);
    border: none;
    border-radius: 1rem;
    color: white;
    font-weight: 600;
    padding: 0.85rem 1.5rem;
    box-shadow: 0 8px 20px rgba(16, 185, 129, 0.25);
    transition: all 0.3s ease;
    width: 100%;
}
.btn-antigravity:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 25px rgba(16, 185, 129, 0.35);
    color: white;
}

/* AI Smart Banner */
.ai-banner-anti {
    background: linear-gradient(to right, #ecfdf5, #ffffff, #f0fdf4);
    border: 1px solid rgba(16, 185, 129, 0.2);
    border-radius: 1.5rem;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1.25rem;
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.05);
    margin-bottom: 2rem;
}
.ai-banner-icon {
    background: linear-gradient(135deg, #34d399, #0ea5e9);
    width: 3rem;
    height: 3rem;
    border-radius: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    box-shadow: 0 8px 15px rgba(16, 185, 129, 0.25);
    color: white;
}
.ai-banner-title {
    font-weight: 800;
    color: #064e3b;
    margin-bottom: 0.2rem;
    font-size: 1.1rem;
}
.ai-banner-text {
    color: #047857;
    font-size: 0.9rem;
    font-weight: 500;
    margin: 0;
}

/* Active Filters */
.filter-chips-container {
    background: rgba(255, 255, 255, 0.5);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.6);
    border-radius: 1.5rem;
    padding: 0.75rem 1.25rem;
    display: inline-flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 2rem;
}
.filter-label {
    font-size: 0.85rem;
    font-weight: 700;
    color: #6b7280;
}
.filter-chip-anti {
    background: white;
    border: 1px solid #d1fae5;
    color: #065f46;
    padding: 0.35rem 1rem;
    border-radius: 2rem;
    font-size: 0.85rem;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(16, 185, 129, 0.05);
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.2s;
}
.filter-chip-anti:hover {
    background: #ecfdf5;
}
.filter-chip-anti a {
    color: #9ca3af;
    text-decoration: none;
    font-weight: bold;
    font-size: 1rem;
    line-height: 1;
}
.filter-chip-anti a:hover {
    color: #ef4444;
}
.clear-all-anti {
    color: #ef4444;
    font-size: 0.85rem;
    font-weight: 600;
    text-decoration: none;
    padding: 0.35rem 0.75rem;
    border-radius: 2rem;
    transition: all 0.2s;
}
.clear-all-anti:hover {
    background: #fef2f2;
}

/* Section Title */
.section-title-anti {
    font-size: 1.75rem;
    font-weight: 800;
    color: #1f2937;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
}
.section-title-indicator {
    width: 6px;
    height: 32px;
    background: linear-gradient(to bottom, #34d399, #059669);
    border-radius: 10px;
}

/* Antigravity Cards */
.card-anti {
    border: 1px solid rgba(255, 255, 255, 0.8);
    border-radius: 1.5rem;
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(10px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.04);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
    padding: 0.75rem;
}
.card-anti:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(16, 185, 129, 0.08);
}

.card-anti-img-wrap {
    width: 100%;
    height: 200px;
    border-radius: 1rem;
    overflow: hidden;
    margin-bottom: 1rem;
    background: #f3f4f6;
    position: relative;
}
.card-anti-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s ease;
}
.card-anti:hover .card-anti-img {
    transform: scale(1.05);
}

.card-anti-body {
    padding: 0 0.5rem 0.5rem 0.5rem;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.product-cat-anti {
    font-size: 0.7rem;
    text-transform: uppercase;
    font-weight: 800;
    color: #059669;
    letter-spacing: 0.05em;
    margin-bottom: 0.25rem;
}

.product-title-anti {
    font-size: 1.15rem;
    font-weight: 800;
    color: #111827;
    margin-bottom: 0.5rem;
    transition: color 0.2s;
    line-height: 1.3;
}
.card-anti:hover .product-title-anti {
    color: #059669;
}

.farmer-info-anti {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: #f9fafb;
    padding: 0.5rem 0.75rem;
    border-radius: 0.75rem;
    font-size: 0.8rem;
    color: #4b5563;
    font-weight: 500;
    margin-bottom: 1rem;
    border: 1px solid #f3f4f6;
}
.farmer-icon {
    width: 20px;
    height: 20px;
    background: #d1fae5;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.6rem;
}

.product-price-anti {
    font-size: 1.5rem;
    font-weight: 800;
    color: #047857;
    display: flex;
    align-items: baseline;
    gap: 0.25rem;
    margin-top: auto;
    padding-top: 0.5rem;
    margin-bottom: 1rem;
}
.product-price-anti span {
    font-size: 0.85rem;
    font-weight: 600;
    color: #6b7280;
}

/* Action Buttons inside Card */
.btn-buy-anti {
    background: #111827;
    color: white;
    border-radius: 1rem;
    font-weight: 700;
    padding: 0.85rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    border: none;
    width: 100%;
    text-align: center;
    text-decoration: none;
    display: block;
}
.btn-buy-anti:hover {
    background: #059669;
    box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
    transform: translateY(-2px);
    color: white;
}

.btn-details-anti {
    background: #ecfdf5;
    color: #047857;
    border-radius: 1rem;
    font-weight: 700;
    padding: 0.85rem;
    transition: all 0.2s;
    border: 1px solid rgba(16, 185, 129, 0.2);
    width: 100%;
    text-align: center;
    text-decoration: none;
    display: block;
}
.btn-details-anti:hover {
    background: #d1fae5;
    color: #047857;
}

.btn-group-anti {
    display: flex;
    gap: 0.5rem;
}
.btn-group-anti .btn {
    flex: 1;
    border-radius: 1rem;
    font-weight: 700;
    padding: 0.85rem;
}

/* AI Recommend Badge */
.ai-badge-anti {
    position: absolute;
    top: 0.75rem;
    left: 0.75rem;
    background: linear-gradient(135deg, #34d399, #0ea5e9);
    color: white;
    font-size: 0.65rem;
    font-weight: 800;
    padding: 0.4rem 0.75rem;
    border-radius: 2rem;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
    z-index: 10;
    letter-spacing: 0.05em;
    text-transform: uppercase;
}

/* Empty State */
.empty-state-anti {
    background: rgba(255, 255, 255, 0.7);
    backdrop-filter: blur(12px);
    border-radius: 2rem;
    padding: 4rem 2rem;
    text-align: center;
    border: 1px solid rgba(255, 255, 255, 0.8);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
    margin-top: 2rem;
}
.empty-icon-wrap {
    width: 7rem;
    height: 7rem;
    background: #ecfdf5;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3.5rem;
    margin: 0 auto 1.5rem auto;
    box-shadow: inset 0 4px 10px rgba(16, 185, 129, 0.1);
}
.empty-title {
    font-size: 1.5rem;
    font-weight: 800;
    color: #1f2937;
    margin-bottom: 0.5rem;
}
.empty-text {
    color: #6b7280;
    font-weight: 500;
    max-width: 400px;
    margin: 0 auto 2rem auto;
}

/* Pagination */
.pagination {
    margin-top: 3rem;
}
.page-item.active .page-link {
    background-color: #059669;
    border-color: #059669;
}
.page-link {
    color: #059669;
    border-radius: 0.5rem;
    margin: 0 0.2rem;
    font-weight: 600;
    border: none;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}
.page-link:hover {
    color: #047857;
    background-color: #ecfdf5;
}

/* Chat Overrides */
#chatbox {
    border-radius: 1.5rem !important;
    border: 1px solid rgba(255,255,255,0.8) !important;
    box-shadow: 0 15px 40px rgba(0,0,0,0.15) !important;
}
.chat-header {
    background: linear-gradient(135deg, #059669, #10b981) !important;
    padding: 1rem !important;
    font-size: 1.1rem;
    letter-spacing: 0.02em;
}
.chat-btn {
    background: linear-gradient(135deg, #059669, #10b981) !important;
    transition: all 0.3s;
}
.chat-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(16,185,129,0.4) !important;
}
</style>

<div class="particles">
    <span></span>
    <span></span>
    <span></span>
    <span></span>
</div>

<div class="container mt-4 mb-5 pb-5">

    <h3 class="page-title">🌿 Fresh Farm Products</h3>

    {{-- SEARCH + FILTER --}}
    <div class="search-glass">
        <form method="GET" action="{{ route('products.index') }}">
            <div class="row g-3 align-items-center">
                <!-- Search -->
                <div class="col-lg-5 col-md-12">
                    <div class="input-group">
                        <span class="input-group-text">🔍</span>
                        <input type="text" name="search" id="searchInput" class="form-control"
                               placeholder="Search fresh organic products..."
                               value="{{ request('search') }}">
                    </div>
                </div>

                <!-- Category -->
                <div class="col-lg-3 col-md-6">
                    <select id="categoryFilter" name="category" class="form-select">
                        <option value="">📦 All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" 
                                {{ request('category') == $cat ? 'selected' : '' }}>
                                {{ ucfirst($cat) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Sort -->
                <div class="col-lg-2 col-md-6">
                    <select id="sortFilter" name="sort" class="form-select">
                        <option value="">✨ Sort By</option>
                        <option value="latest" {{ request('sort')=='latest' ? 'selected':'' }}>Newest First</option>
                        <option value="price_low" {{ request('sort')=='price_low' ? 'selected':'' }}>Price: Low → High</option>
                        <option value="price_high" {{ request('sort')=='price_high' ? 'selected':'' }}>Price: High → Low</option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="col-lg-2 col-md-12 d-flex gap-2">
                    <button class="btn-antigravity flex-grow-1">Search</button>
                </div>
            </div>
        </form>
    </div>

    @if(request()->hasAny(['search','category','sort']))
    <div class="filter-chips-container">
        <span class="filter-label">Filters Applied:</span>

        @if(request('search'))
            <span class="filter-chip-anti">
                🔍 {{ request('search') }}
                <a href="{{ route('products.index', request()->except('search')) }}">✖</a>
            </span>
        @endif

        @if(request('category'))
            <span class="filter-chip-anti">
                📦 {{ request('category') }}
                <a href="{{ route('products.index', request()->except('category')) }}">✖</a>
            </span>
        @endif

        @if(request('sort'))
            <span class="filter-chip-anti">
                ✨ @php
                $sortText = [
                    'latest' => 'Newest',
                    'price_low' => 'Price ↑',
                    'price_high' => 'Price ↓'
                ];
                @endphp
                {{ $sortText[request('sort')] ?? request('sort') }}
                <a href="{{ route('products.index', request()->except('sort')) }}">✖</a>
            </span>
        @endif

        <a href="{{ route('products.index') }}" class="clear-all-anti">Clear All</a>
    </div>
    @endif

    {{-- AI RECOMMENDED --}}
    <div id="recommendedSection">
    @if(!request('search') && isset($recommendedProducts) && $recommendedProducts->count() > 0)

        <div class="section-title-anti mt-2">
            <div class="section-title-indicator"></div>
            Handpicked For You
        </div>

        <div class="ai-banner-anti">
            <div class="ai-banner-icon">🤖</div>
            <div>
                <h5 class="ai-banner-title">Smart Recommendations</h5>
                <p class="ai-banner-text">Curated based on seasonal demand, premium quality, and your preferences.</p>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-4 mb-5">
            @foreach($recommendedProducts as $product)
            <div class="col">
                <div class="card-anti">
                    
                    <div class="card-anti-img-wrap">
                        <span class="ai-badge-anti">✨ Top Pick</span>
                        @if($product->image)
                            <img src="{{ asset('storage/'.$product->image) }}" class="card-anti-img">
                        @else
                            <img src="https://via.placeholder.com/300" class="card-anti-img">
                        @endif
                    </div>

                    <div class="card-anti-body">
                        <p class="product-cat-anti">{{ ucfirst($product->category) }}</p>
                        <h6 class="product-title-anti">{{ ucfirst($product->product_name) }}</h6>
                        
                        <p class="product-price-anti">
                            ₹{{ $product->price }} <span>/ {{ $product->unit }}</span>
                        </p>

                        {{-- Stock Indicator --}}
                        <div class="mb-3" style="font-size: 0.85rem; font-weight: 600;">
                            @if($product->quantity > 10)
                                <span style="color: #059669;">Stock Available: {{ $product->quantity }} {{ $product->unit }} 🟢</span>
                            @elseif($product->quantity > 0 && $product->quantity <= 10)
                                <span style="color: #dc3545;">Stock Available: {{ $product->quantity }} {{ $product->unit }} 🔴 Low Stock</span>
                            @else
                                <span style="color: #dc3545;">Out of Stock 🔴</span>
                            @endif
                        </div>

                        <a href="{{ route('products.show',$product->id) }}" class="btn-details-anti mb-2">
                            View Details
                        </a>

                        @if(session()->has('farmer_id') && session('farmer_id') == $product->farmer_id)
                            <div class="btn-group-anti">
                                <a href="{{ route('products.edit',$product->id) }}" class="btn btn-warning text-white" style="border-radius:1rem; font-weight:bold;">Edit</a>
                                <form action="{{ route('products.destroy',$product->id) }}" method="POST" style="flex:1;">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger w-100" style="border-radius:1rem; font-weight:bold;">Delete</button>
                                </form>
                            </div>
                        @else
                            @if($product->quantity > 0)
                                <a href="{{ route('buy.request',$product->id) }}" class="btn-buy-anti">
                                    🛒 Buy Now
                                </a>
                            @else
                                <button class="btn-buy-anti" style="background: #9ca3af; cursor: not-allowed;" disabled>
                                    Out of Stock
                                </button>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <hr class="my-5" style="opacity: 0.1;">
    @endif
    </div>

    {{-- NEARBY PRODUCTS --}}
    <div id="nearbySection">
    @if(!request('search') && isset($nearbyProducts) && $nearbyProducts->count() > 0)

        <div class="section-title-anti mt-2">
            <div class="section-title-indicator" style="background: linear-gradient(to bottom, #f59e0b, #d97706);"></div>
            Local Farmers Near You
        </div>

        <div class="ai-banner-anti" style="background: linear-gradient(to right, #fffbeb, #ffffff, #fef3c7); border-color: rgba(245, 158, 11, 0.2);">
            <div class="ai-banner-icon" style="background: linear-gradient(135deg, #fcd34d, #f59e0b);">📍</div>
            <div>
                <h5 class="ai-banner-title" style="color: #92400e;">Fresh from Your Area</h5>
                <p class="ai-banner-text" style="color: #b45309;">Support local agriculture. These products are harvested right in your district or state.</p>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-4 mb-5">
            @foreach($nearbyProducts as $product)
            <div class="col">
                <div class="card-anti">
                    
                    <div class="card-anti-img-wrap">
                        <span class="ai-badge-anti" style="background: linear-gradient(135deg, #fcd34d, #f59e0b);">📍 Local Match</span>
                        @if($product->image)
                            <img src="{{ asset('storage/'.$product->image) }}" class="card-anti-img">
                        @else
                            <img src="https://via.placeholder.com/300" class="card-anti-img">
                        @endif
                    </div>

                    <div class="card-anti-body">
                        <p class="product-cat-anti" style="color: #d97706;">{{ ucfirst($product->category) }}</p>
                        <h6 class="product-title-anti">{{ ucfirst($product->product_name) }}</h6>
                        
                        <div class="farmer-info-anti" style="background: #fffbeb; border-color: #fef3c7;">
                            <div class="farmer-icon" style="background: #fde68a;">🚜</div>
                            {{ $product->farmer->name ?? 'Local Farmer' }}
                        </div>
                        
                        <p class="product-price-anti" style="color: #b45309;">
                            ₹{{ $product->price }} <span>/ {{ $product->unit }}</span>
                        </p>

                        {{-- Stock Indicator --}}
                        <div class="mb-3" style="font-size: 0.85rem; font-weight: 600;">
                            @if($product->quantity > 10)
                                <span style="color: #059669;">Stock Available: {{ $product->quantity }} {{ $product->unit }} 🟢</span>
                            @elseif($product->quantity > 0 && $product->quantity <= 10)
                                <span style="color: #dc3545;">Stock Available: {{ $product->quantity }} {{ $product->unit }} 🔴 Low Stock</span>
                            @else
                                <span style="color: #dc3545;">Out of Stock 🔴</span>
                            @endif
                        </div>

                        <a href="{{ route('products.show',$product->id) }}" class="btn-details-anti mb-2" style="background: #fffbeb; color: #b45309; border-color: rgba(245, 158, 11, 0.2);">
                            View Details
                        </a>

                        @if(session()->has('farmer_id') && session('farmer_id') == $product->farmer_id)
                            <div class="btn-group-anti">
                                <a href="{{ route('products.edit',$product->id) }}" class="btn btn-warning text-white" style="border-radius:1rem; font-weight:bold;">Edit</a>
                                <form action="{{ route('products.destroy',$product->id) }}" method="POST" style="flex:1;">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger w-100" style="border-radius:1rem; font-weight:bold;">Delete</button>
                                </form>
                            </div>
                        @else
                            @if($product->quantity > 0)
                                <a href="{{ route('buy.request',$product->id) }}" class="btn-buy-anti">
                                    🛒 Buy Now
                                </a>
                            @else
                                <button class="btn-buy-anti" style="background: #9ca3af; cursor: not-allowed;" disabled>
                                    Out of Stock
                                </button>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <hr class="my-5" style="opacity: 0.1;">
    @endif
    </div>

    {{-- ALL PRODUCTS --}}
    <div class="section-title-anti">
        <div class="section-title-indicator" style="background: #4b5563;"></div>
        Explore Marketplace
    </div>
    
    <div id="productList">
        @include('partials.products')
    </div>

    {{-- PAGINATION --}}
    <div class="mt-5 d-flex justify-content-center">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>

</div>

<!-- ALPINE.JS CDN -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<style>
/* Antigravity Glassmorphism Window */
.chat-window-anti {
    position: fixed;
    bottom: 110px;
    right: 30px;
    width: 360px;
    height: 520px;
    background: rgba(255, 255, 255, 0.65);
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
    border: 1px solid rgba(255, 255, 255, 0.8);
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15), inset 0 0 0 1px rgba(255, 255, 255, 0.5);
    border-radius: 1.75rem;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    transform-origin: bottom right;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

/* Z-Axis Floating Animations */
@keyframes floatBubble {
    0% { transform: translateY(20px) scale(0.9) translateZ(0); opacity: 0; }
    100% { transform: translateY(0) scale(1) translateZ(10px); opacity: 1; }
}

.msg-bubble-wrap {
    animation: floatBubble 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
    transform-style: preserve-3d;
    perspective: 1000px;
    margin-bottom: 0.75rem;
}

.bot-bubble {
    background: rgba(255, 255, 255, 0.9);
    border: 1px solid rgba(229, 231, 235, 0.8);
    color: #1f2937;
    padding: 0.85rem 1.1rem;
    border-radius: 1.25rem 1.25rem 1.25rem 0.25rem;
    font-size: 0.9rem;
    line-height: 1.4;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
    display: inline-block;
    max-width: 90%;
}

.user-bubble {
    background: linear-gradient(135deg, #059669, #10b981);
    color: white;
    padding: 0.85rem 1.1rem;
    border-radius: 1.25rem 1.25rem 0.25rem 1.25rem;
    font-size: 0.9rem;
    line-height: 1.4;
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.2);
    display: inline-block;
    max-width: 90%;
    text-align: left;
}

/* Horizontal Suggestion Chips */
.chips-scroll-container {
    display: flex;
    gap: 0.4rem;
    overflow-x: auto;
    padding: 0.5rem 1rem;
    background: rgba(249, 250, 251, 0.5);
    border-top: 1px solid rgba(229, 231, 235, 0.5);
    scrollbar-width: none;
}
.chips-scroll-container::-webkit-scrollbar { display: none; }

.chip-btn-anti {
    white-space: nowrap;
    background: white;
    border: 1px solid rgba(16, 185, 129, 0.3);
    color: #047857;
    font-weight: 700;
    font-size: 0.75rem;
    padding: 0.4rem 0.85rem;
    border-radius: 1.5rem;
    transition: all 0.2s ease;
    box-shadow: 0 2px 5px rgba(0,0,0,0.02);
    cursor: pointer;
}
.chip-btn-anti:hover {
    background: #10b981;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(16, 185, 129, 0.2);
}

/* Typing Indicator Animation */
.typing-dots {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.5rem 0.8rem;
    background: rgba(255, 255, 255, 0.8);
    border-radius: 1rem;
    border: 1px solid #e5e7eb;
}
.typing-dots span {
    width: 6px;
    height: 6px;
    background-color: #10b981;
    border-radius: 50%;
    animation: typingPulse 1.4s infinite ease-in-out both;
}
.typing-dots span:nth-child(1) { animation-delay: -0.32s; }
.typing-dots span:nth-child(2) { animation-delay: -0.16s; }

@keyframes typingPulse {
    0%, 80%, 100% { transform: scale(0); opacity: 0.3; }
    40% { transform: scale(1); opacity: 1; }
}

/* Floating Action Button Trigger */
.fab-trigger-anti {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 65px;
    height: 65px;
    background: linear-gradient(135deg, #059669, #10b981);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 28px;
    cursor: pointer;
    box-shadow: 0 10px 25px rgba(16, 185, 129, 0.4);
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    z-index: 10000;
    border: 2px solid rgba(255, 255, 255, 0.4);
}
.fab-trigger-anti:hover {
    transform: scale(1.1) rotate(5deg);
    box-shadow: 0 15px 35px rgba(16, 185, 129, 0.5);
}

/* Embedded Product Payload Card */
.chat-product-card {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    background: white;
    border: 1px solid #e5e7eb;
    padding: 0.5rem;
    border-radius: 1rem;
    margin-top: 0.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}
.chat-product-img {
    width: 50px;
    height: 50px;
    border-radius: 0.75rem;
    object-fit: cover;
}
.chat-product-info {
    flex-grow: 1;
    text-align: left;
}
.chat-product-title {
    font-size: 0.85rem;
    font-weight: 700;
    color: #111827;
    margin: 0;
}
.chat-product-price {
    font-size: 0.8rem;
    font-weight: 600;
    color: #059669;
    margin: 0;
}
.chat-product-btn {
    font-size: 0.75rem;
    font-weight: 700;
    background: #ecfdf5;
    color: #059669;
    padding: 0.3rem 0.6rem;
    border-radius: 0.5rem;
    text-decoration: none;
    border: 1px solid #d1fae5;
    transition: all 0.2s;
}
.chat-product-btn:hover {
    background: #059669;
    color: white;
}
</style>

<!-- ALPINE.JS CHATBOT ROOT -->
<div x-data="initChatbotEngine()" class="chatbot-root-container">

    <!-- Floating Trigger Button -->
    <div @click="toggle()" class="fab-trigger-anti">
        <span x-text="open ? '✖' : '🤖'" :style="open ? 'font-size: 1.2rem;' : ''"></span>
    </div>

    <!-- Antigravity Chat Window -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-75 translate-y-12"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-75 translate-y-12"
         class="chat-window-anti" 
         style="display: none;">

        <!-- Header -->
        <div class="p-3 d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, #059669, #10b981); color: white;">
            <div class="d-flex align-items-center gap-2">
                <div style="position: relative;">
                    <span style="font-size: 1.5rem;">🤖</span>
                    <span style="position: absolute; bottom: 0; right: 0; width: 10px; height: 10px; background: #34d399; border-radius: 50%; border: 2px solid #059669;"></span>
                </div>
                <div>
                    <h6 class="m-0 fw-bold" style="font-size: 0.95rem; letter-spacing: -0.01em;">AgriConnect AI</h6>
                    <small style="font-size: 0.7rem; opacity: 0.9;">Smart Assistant </small>
                </div>
            </div>
            <button @click="open = false" class="btn btn-sm text-white border-0" style="background: rgba(255,255,255,0.2); border-radius: 50%; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center;">✕</button>
        </div>

        <!-- Message Scrolling Body -->
        <div id="chat-scroll-area" class="flex-grow-1 p-3" style="overflow-y: auto; background: rgba(255, 255, 255, 0.3);">
            
            <template x-for="(msg, idx) in messages" :key="idx">
                <div class="msg-bubble-wrap" :style="msg.sender === 'user' ? 'text-align: right;' : 'text-align: left;'">
                    
                    <!-- Bot Text Bubble -->
                    <template x-if="msg.sender === 'bot'">
                        <div>
                            <div class="bot-bubble" x-html="msg.text"></div>
                            
                            <!-- Attached Product Payloads Rendering -->
                            <template x-if="msg.payload && msg.payload.length > 0">
                                <div class="mt-2">
                                    <template x-for="prod in msg.payload" :key="prod.id">
                                        <div class="chat-product-card">
                                            <img :src="prod.image" class="chat-product-img">
                                            <div class="chat-product-info">
                                                <p class="chat-product-title" x-text="prod.name"></p>
                                                <p class="chat-product-price" x-text="'₹' + prod.price + (prod.unit ? ' / ' + prod.unit : '')"></p>
                                            </div>
                                            <a :href="'/products/' + prod.id" class="chat-product-btn">View</a>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </template>

                    <!-- User Text Bubble -->
                    <template x-if="msg.sender === 'user'">
                        <div class="user-bubble" x-text="msg.text"></div>
                    </template>

                </div>
            </template>

            <!-- Live Typing Indicator Overlay -->
            <div x-show="typing" class="msg-bubble-wrap text-left">
                <div class="typing-dots">
                    <span></span><span></span><span></span>
                </div>
            </div>

        </div>

        <!-- Horizontal Quick Suggestion Chips -->
        <div class="chips-scroll-container">
            <template x-for="(suggest, sIdx) in currentSuggestions" :key="sIdx">
                <span @click="sendQuery(suggest)" class="chip-btn-anti" x-text="suggest"></span>
            </template>
        </div>

        <!-- Input Box -->
        <div class="p-2" style="background: rgba(255, 255, 255, 0.8); border-top: 1px solid rgba(229, 231, 235, 0.6);">
            <form @submit.prevent="sendQuery()" class="d-flex gap-2">
                <input type="text" x-model="input" placeholder="Ask about items or predict prices..." class="form-control" style="border-radius: 1.25rem; font-size: 0.85rem; padding: 0.6rem 1rem; border: 1px solid #e5e7eb; background: rgba(255, 255, 255, 0.9);">
                <button type="submit" class="btn text-white px-3" style="background: linear-gradient(135deg, #059669, #10b981); border-radius: 1.25rem; font-weight: bold; font-size: 0.85rem;">➔</button>
            </form>
        </div>

    </div>

</div>

<script>
function initChatbotEngine() {
    return {
        open: false,
        typing: false,
        input: '',
        messages: [
            { 
                sender: 'bot', 
                text: 'Hello! 👋 Welcome to AgriConnect AI. Try asking for  low price products,  general recommendations or Buying guides!',
                payload: []
            }
        ],
        currentSuggestions: ["cheap vegetables", "show fruits", "recommend products"],
        
        init() {
            // Watch messages array to automatically smooth scroll to bottom
            this.$watch('messages', () => {
                this.$nextTick(() => {
                    const box = document.getElementById('chat-scroll-area');
                    if (box) box.scrollTo({ top: box.scrollHeight, behavior: 'smooth' });
                });
            });
            this.$watch('typing', () => {
                this.$nextTick(() => {
                    const box = document.getElementById('chat-scroll-area');
                    if (box) box.scrollTo({ top: box.scrollHeight, behavior: 'smooth' });
                });
            });
        },

        toggle() {
            this.open = !this.open;
            if (this.open) {
                this.$nextTick(() => {
                    const box = document.getElementById('chat-scroll-area');
                    if (box) box.scrollTop = box.scrollHeight;
                });
            }
        },

        sendQuery(customStr = null) {
            const queryText = customStr !== null ? customStr : this.input.trim();
            if (!queryText) return;

            if (customStr === null) {
                this.input = '';
            }

            // Append user bubble
            this.messages.push({ sender: 'user', text: queryText });
            this.typing = true;

            // Transmit AJAX Request
            fetch('/chatbot', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ message: queryText })
            })
            .then(res => res.json())
            .then(data => {
                this.typing = false;
                
                // Dynamically update suggestion chips if supplied
                if (data.suggestions && data.suggestions.length > 0) {
                    this.currentSuggestions = data.suggestions;
                }

                this.messages.push({
                    sender: 'bot',
                    text: data.message || data.reply || "Response received.",
                    payload: data.payload || []
                });
            })
            .catch(err => {
                this.typing = false;
                this.messages.push({
                    sender: 'bot',
                    text: '⚠️ Network error communicating with AI services. Please try again.',
                    payload: []
                });
            });
        }
    }
}

// Ensure original marketplace live filtering logic remains intact
document.addEventListener("DOMContentLoaded", function () {
    const search = document.getElementById("searchInput");
    const category = document.getElementById("categoryFilter");
    const sort = document.getElementById("sortFilter");
    const recommended = document.getElementById("recommendedSection");
    let timer;

    function fetchProducts() {
        const params = new URLSearchParams();
        if(search?.value) params.append('search', search.value);
        if(category?.value) params.append('category', category.value);
        if(sort?.value) params.append('sort', sort.value);

        const listWrap = document.getElementById("productList");
        if (!listWrap) return;

        listWrap.style.opacity = '0.5';

        fetch("{{ route('products.index') }}?" + params.toString(), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.text())
        .then(data => {
            listWrap.innerHTML = data;
            listWrap.style.opacity = '1';
        });
    }

    search?.addEventListener("keyup", () => {
        if(search.value.trim() !== ""){
            if(recommended) recommended.style.display = "none";
        } else {
            if(recommended) recommended.style.display = "block";
        }
        clearTimeout(timer);
        timer = setTimeout(fetchProducts, 400);
    });

    category?.addEventListener("change", fetchProducts);
    sort?.addEventListener("change", fetchProducts);
});
</script>

@endsection