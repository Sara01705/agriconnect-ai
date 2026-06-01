@extends('layouts.app')

@section('content')
<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .edit-container {
        max-width: 850px;
        margin: 40px auto;
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        animation: fadeInUp 0.5s ease-out forwards;
    }

    .edit-header {
        background: linear-gradient(135deg, #1e7e34 0%, #28a745 100%);
        padding: 40px 30px;
        color: white;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .edit-header::before {
        content: '';
        position: absolute;
        top: -50px; right: -50px;
        width: 150px; height: 150px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .edit-header h3 {
        margin: 0;
        font-weight: 800;
        font-size: 2rem;
        position: relative;
        z-index: 1;
    }

    .edit-header p {
        margin-top: 10px;
        opacity: 0.9;
        margin-bottom: 0;
        font-size: 1.05rem;
        position: relative;
        z-index: 1;
    }

    .edit-body {
        padding: 40px;
    }

    .form-label {
        font-weight: 600;
        color: #2d4a35;
        margin-bottom: 8px;
    }

    .form-control {
        border-radius: 12px;
        padding: 14px 15px;
        border: 1px solid #d0e8d5;
        background-color: #f8fcf9;
        transition: all 0.3s ease;
        font-size: 1rem;
    }

    .form-control:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 0.25rem rgba(40, 167, 69, 0.25);
        background-color: #ffffff;
    }

    .input-group-text {
        background-color: #e9f7ec;
        border: 1px solid #d0e8d5;
        color: #1e7e34;
        border-radius: 12px 0 0 12px;
        border-right: none;
        padding: 0 18px;
    }

    .form-control.with-icon {
        border-left: none;
        border-radius: 0 12px 12px 0;
    }

    .status-group {
        display: flex;
        gap: 15px;
    }

    .status-label {
        flex: 1;
        text-align: center;
        padding: 12px;
        border-radius: 12px;
        border: 2px solid #d0e8d5;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.2s;
        color: #6c757d;
        background: #f8fcf9;
    }

    .status-radio:checked + .status-label-available {
        background-color: #d4edda;
        border-color: #28a745;
        color: #155724;
        box-shadow: 0 4px 10px rgba(40, 167, 69, 0.15);
    }

    .status-radio:checked + .status-label-unavailable {
        background-color: #f8d7da;
        border-color: #dc3545;
        color: #721c24;
        box-shadow: 0 4px 10px rgba(220, 53, 69, 0.15);
    }

    .est-revenue-box {
        background: linear-gradient(to right, #f0fdf4, #ffffff);
        border: 1px dashed #28a745;
        border-radius: 12px;
        padding: 15px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 20px;
    }

    .btn-update {
        background: linear-gradient(135deg, #1e7e34 0%, #28a745 100%);
        border: none;
        border-radius: 12px;
        padding: 14px 35px;
        font-weight: 600;
        font-size: 1.1rem;
        transition: transform 0.2s;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    }

    .btn-update:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
    }

    .btn-cancel {
        border-radius: 12px;
        padding: 14px 35px;
        font-weight: 600;
        color: #6c757d;
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        transition: all 0.2s;
    }

    .btn-cancel:hover {
        background: #e2e6ea;
        color: #495057;
    }
</style>

<div class="edit-container">
    <div class="edit-header">
        <h3>✏️ Manage Product</h3>
        <p>Update inventory levels, adjust pricing, and track potential revenue.</p>
    </div>

    <div class="edit-body">
        <form method="POST" action="{{ route('products.update', $product->id) }}" id="editProductForm">
            @csrf
            @method('PUT')

            <div class="row g-4">
                {{-- Product Overview Profile --}}
                <div class="col-12 mb-2">
                    <div class="d-flex align-items-center p-3 rounded" style="background: rgba(40, 167, 69, 0.05); border: 1px solid rgba(40, 167, 69, 0.1);">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->product_name }}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 12px; margin-right: 20px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                        @else
                            <div style="width: 80px; height: 80px; border-radius: 12px; background: linear-gradient(135deg, #1e7e34, #28a745); color: white; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin-right: 20px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                                <i class="bi bi-box-seam"></i>
                            </div>
                        @endif
                        <div>
                            <h4 style="font-weight: 800; color: #1e7e34; margin-bottom: 5px;">{{ $product->product_name }}</h4>
                            <span class="badge bg-success" style="font-size: 0.85rem; padding: 6px 12px; border-radius: 8px;"><i class="bi bi-tag-fill"></i> {{ ucfirst($product->category) }}</span>
                        </div>
                    </div>
                </div>

                {{-- Hidden Fields for Controller Update --}}
                <input type="hidden" name="product_name" value="{{ $product->product_name }}">
                <input type="hidden" name="category" value="{{ $product->category }}">

                {{-- Price --}}
                <div class="col-md-4">
                    <label class="form-label">Price (per unit)</label>
                    <div class="input-group">
                        <span class="input-group-text">₹</span>
                        <input type="number" step="0.01" name="price" id="inputPrice" value="{{ $product->price }}" class="form-control with-icon" required>
                    </div>
                </div>

                {{-- Quantity --}}
                <div class="col-md-4">
                    <label class="form-label">Available Stock</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-layers"></i></span>
                        <input type="number" name="quantity" id="inputQuantity" value="{{ $product->quantity }}" class="form-control with-icon" required>
                    </div>
                </div>

                {{-- Unit --}}
                <div class="col-md-4">
                    <label class="form-label">Unit of Measure</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-speedometer2"></i></span>
                        <input type="text" name="unit" value="{{ $product->unit }}" class="form-control with-icon" required>
                    </div>
                </div>
                
                {{-- Estimated Revenue Widget --}}
                <div class="col-12 mt-3">
                    <div class="est-revenue-box">
                        <div class="d-flex align-items-center gap-2 text-success">
                            <i class="bi bi-calculator fs-4"></i>
                            <div>
                                <h6 class="mb-0 fw-bold">Estimated Stock Value</h6>
                                <small class="text-muted">Total potential revenue from current stock</small>
                            </div>
                        </div>
                        <h3 class="mb-0 fw-bold text-success" id="estRevenueDisplay">₹0.00</h3>
                    </div>
                </div>

                {{-- Status Selection --}}
                <div class="col-12">
                    <label class="form-label">Product Status</label>
                    <div class="status-group">
                        <label style="flex: 1;">
                            <input type="radio" name="status" value="available" class="d-none status-radio" {{ strtolower($product->status) == 'available' ? 'checked' : '' }}>
                            <div class="status-label status-label-available">
                                <i class="bi bi-check-circle-fill"></i> Available for Sale
                            </div>
                        </label>
                        <label style="flex: 1;">
                            <input type="radio" name="status" value="unavailable" class="d-none status-radio" {{ strtolower($product->status) == 'unavailable' ? 'checked' : '' }}>
                            <div class="status-label status-label-unavailable">
                                <i class="bi bi-x-circle-fill"></i> Hide / Out of Stock
                            </div>
                        </label>
                    </div>
                    <small class="text-muted mt-2 d-block"><i class="bi bi-info-circle"></i> Tip: Status switches to 'Unavailable' automatically if stock reaches 0.</small>
                </div>

                {{-- Description --}}
                <div class="col-12">
                    <label class="form-label">Product Details & Description</label>
                    <textarea name="description" class="form-control" rows="4" placeholder="Briefly describe the product, freshness, and delivery options...">{{ $product->description }}</textarea>
                </div>
            </div>

            {{-- Actions --}}
            <div class="d-flex justify-content-end gap-3 mt-5 pt-4 border-top">
                <a href="{{ route('farmer.dashboard') }}" class="btn btn-cancel">
                    <i class="bi bi-x-circle"></i> Cancel
                </a>
                <button type="submit" class="btn btn-update text-white">
                    <i class="bi bi-cloud-arrow-up-fill"></i> Save Changes
                </button>
            </div>

        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const priceInput = document.getElementById('inputPrice');
        const qtyInput = document.getElementById('inputQuantity');
        const revenueDisplay = document.getElementById('estRevenueDisplay');
        const statusRadios = document.querySelectorAll('.status-radio');

        function updateRevenue() {
            const price = parseFloat(priceInput.value) || 0;
            const qty = parseFloat(qtyInput.value) || 0;
            const total = price * qty;
            
            // Format as currency
            revenueDisplay.innerHTML = '₹' + total.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            
            // Auto-switch status to unavailable if qty is 0
            if(qty === 0) {
                document.querySelector('input[name="status"][value="unavailable"]').checked = true;
            } else if (qty > 0 && document.querySelector('input[name="status"][value="unavailable"]').checked) {
                // Optional: Auto switch to available when restocked
                document.querySelector('input[name="status"][value="available"]').checked = true;
            }
        }

        // Attach listeners
        priceInput.addEventListener('input', updateRevenue);
        qtyInput.addEventListener('input', updateRevenue);

        // Run once on load
        updateRevenue();
    });
</script>
@endsection
