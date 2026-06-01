<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-4">

@forelse($products as $product)

<div class="col">
    <div class="card-anti">
        
        <div class="card-anti-img-wrap">
            @if($product->image)
                <img src="{{ asset('storage/'.$product->image) }}" class="card-anti-img">
            @else
                <img src="https://via.placeholder.com/300" class="card-anti-img">
            @endif
        </div>

        <div class="card-anti-body">
            
            <p class="product-cat-anti">{{ ucfirst($product->category) }}</p>
            <h6 class="product-title-anti">{{ ucfirst($product->product_name) }}</h6>
            
            <div class="farmer-info-anti">
                <div class="farmer-icon">👨‍🌾</div>
                <span style="flex-grow:1; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                    {{ $product->farmer->name ?? 'Unknown Farmer' }}
                </span>
                @if(isset($product->farmer) && $product->farmer->verified)
                    <span style="color:#3b82f6; font-size:1rem; font-weight:bold;" title="Verified Farmer">✓</span>
                @endif
            </div>

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

@empty

<div class="col-12">
    <div class="empty-state-anti">
        <div class="empty-icon-wrap">
            📦
        </div>
        <h3 class="empty-title">No Products Found</h3>
        <p class="empty-text">We couldn't find any fresh produce matching your current filters. Try adjusting your search or category.</p>
        <a href="{{ route('products.index') }}" class="btn-antigravity" style="display:inline-block; width:auto; padding:0.85rem 2.5rem; text-decoration:none;">
            Clear Filters
        </a>
    </div>
</div>

@endforelse

</div>