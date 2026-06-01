@extends('layouts.app')

@section('content')

<style>
    /* ── Page wrapper ── */
    .create-wrapper {
        max-width: 820px;
        margin: 0 auto;
        padding-bottom: 60px;
    }

    /* ── Card ── */
    .create-card {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(40, 167, 69, 0.15), 0 2px 12px rgba(0,0,0,0.08);
        animation: slideUp 0.5s ease both;
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(24px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ── Header ── */
    .create-header {
        background: linear-gradient(135deg, #1e7e34 0%, #28a745 60%, #52c96e 100%);
        padding: 32px 36px;
        position: relative;
        overflow: hidden;
    }

    .create-header::before {
        content: '';
        position: absolute;
        top: -40px; right: -40px;
        width: 180px; height: 180px;
        border-radius: 50%;
        background: rgba(255,255,255,0.08);
    }

    .create-header::after {
        content: '';
        position: absolute;
        bottom: -60px; left: -20px;
        width: 220px; height: 220px;
        border-radius: 50%;
        background: rgba(255,255,255,0.05);
    }

    .create-header h4 {
        font-size: 1.6rem;
        font-weight: 700;
        letter-spacing: 0.3px;
        position: relative;
        z-index: 1;
    }

    .create-header p {
        opacity: 0.85;
        font-size: 0.92rem;
        position: relative;
        z-index: 1;
    }

    /* ── Body ── */
    .create-body {
        background: #ffffff;
        padding: 36px 40px;
    }

    /* ── Section divider ── */
    .form-section-title {
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        color: #1e7e34;
        border-bottom: 2px solid #e6f4ea;
        padding-bottom: 8px;
        margin-bottom: 20px;
        margin-top: 28px;
    }

    .form-section-title:first-of-type {
        margin-top: 0;
    }

    /* ── Labels ── */
    .form-label {
        font-weight: 600;
        font-size: 0.875rem;
        color: #2d4a35;
        margin-bottom: 6px;
    }

    .required-star {
        color: #dc3545;
        margin-left: 2px;
    }

    /* ── Inputs ── */
    .form-control,
    .form-select {
        border: 1.5px solid #d0e8d5;
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 0.925rem;
        transition: border-color 0.25s, box-shadow 0.25s, background 0.25s;
        background: #fafffe;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.18);
        background: #ffffff;
        outline: none;
    }

    .form-control::placeholder {
        color: #a8c4ae;
    }

    /* ── Input group icons ── */
    .input-group-text {
        background: #e8f5ec;
        border: 1.5px solid #d0e8d5;
        border-right: none;
        border-radius: 10px 0 0 10px;
        font-size: 1.05rem;
        color: #1e7e34;
        padding: 10px 14px;
    }

    .input-group .form-control,
    .input-group .form-select {
        border-left: none;
        border-radius: 0 10px 10px 0;
    }

    .input-group .form-control:focus,
    .input-group .form-select:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.18);
    }

    /* ── Textarea ── */
    textarea.form-control {
        resize: vertical;
        min-height: 90px;
    }

    /* ── AI Suggestion Box ── */
    .ai-suggestion-box {
        display: none;
        background: linear-gradient(135deg, #f1fdf4 0%, #e8f5ec 100%);
        border-left: 4px solid #28a745;
        border-radius: 10px;
        padding: 14px 18px;
        margin-top: 10px;
        font-size: 0.875rem;
        color: #1e5c2a;
        animation: fadeInBox 0.4s ease both;
    }

    @keyframes fadeInBox {
        from { opacity: 0; transform: translateY(-8px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .ai-suggestion-box.show {
        display: block;
    }

    .ai-suggestion-box .ai-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #28a745;
        color: white;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        padding: 3px 9px;
        border-radius: 20px;
        margin-bottom: 8px;
    }

    .ai-stats {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
        margin-top: 6px;
    }

    .ai-stat {
        display: flex;
        flex-direction: column;
    }

    .ai-stat-label {
        font-size: 0.72rem;
        font-weight: 600;
        color: #4a8c5c;
        text-transform: uppercase;
        letter-spacing: 0.6px;
    }

    .ai-stat-value {
        font-size: 1rem;
        font-weight: 700;
        color: #1e5c2a;
    }

    /* ── Image upload ── */
    .image-upload-area {
        border: 2px dashed #b2d9bc;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        background: #f8fdf9;
        cursor: pointer;
        transition: border-color 0.25s, background 0.25s;
        position: relative;
    }

    .image-upload-area:hover {
        border-color: #28a745;
        background: #f1fdf4;
    }

    .image-upload-area input[type="file"] {
        opacity: 0;
        position: absolute;
        inset: 0;
        cursor: pointer;
    }

    .image-upload-area .upload-icon {
        font-size: 2rem;
        color: #28a745;
        display: block;
        margin-bottom: 6px;
    }

    .image-upload-area .upload-text {
        font-size: 0.875rem;
        color: #5a8f65;
        font-weight: 500;
    }

    /* ── Image preview ── */
    .preview-container {
        display: none;
        margin-top: 14px;
        border-radius: 12px;
        overflow: hidden;
        border: 2px solid #d0e8d5;
        position: relative;
        max-height: 200px;
    }

    .preview-container img {
        width: 100%;
        max-height: 200px;
        object-fit: cover;
        display: block;
    }

    .preview-container .remove-preview {
        position: absolute;
        top: 8px; right: 8px;
        background: rgba(220, 53, 69, 0.85);
        color: white;
        border: none;
        border-radius: 50%;
        width: 28px; height: 28px;
        font-size: 14px;
        line-height: 28px;
        text-align: center;
        cursor: pointer;
        padding: 0;
        transition: background 0.2s;
    }

    .preview-container .remove-preview:hover {
        background: rgba(220, 53, 69, 1);
    }

    /* ── Buttons ── */
    .btn-submit {
        background: linear-gradient(135deg, #1e7e34, #28a745);
        border: none;
        border-radius: 10px;
        padding: 12px 36px;
        font-weight: 700;
        font-size: 0.95rem;
        color: white;
        letter-spacing: 0.3px;
        box-shadow: 0 4px 14px rgba(40,167,69,0.35);
        transition: transform 0.2s, box-shadow 0.2s, filter 0.2s;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(40,167,69,0.45);
        filter: brightness(1.08);
        color: white;
    }

    .btn-back {
        border: 1.5px solid #b2d9bc;
        border-radius: 10px;
        padding: 11px 26px;
        font-weight: 600;
        font-size: 0.9rem;
        color: #3a7a4a;
        background: transparent;
        transition: background 0.2s, border-color 0.2s, color 0.2s, transform 0.2s;
    }

    .btn-back:hover {
        background: #e8f5ec;
        border-color: #28a745;
        color: #1e7e34;
        transform: translateX(-2px);
    }

    /* ── Validation errors ── */
    .alert-validation {
        background: #fff5f5;
        border-left: 4px solid #dc3545;
        border-radius: 10px;
        padding: 14px 18px;
        margin-bottom: 24px;
        font-size: 0.875rem;
        color: #721c24;
    }

    .alert-validation ul {
        margin: 0;
        padding-left: 18px;
    }

    /* ── Success alert ── */
    .alert-success-custom {
        background: #f1fdf4;
        border-left: 4px solid #28a745;
        border-radius: 10px;
        padding: 14px 18px;
        margin-bottom: 24px;
        font-size: 0.875rem;
        color: #1e5c2a;
        font-weight: 500;
    }

    /* ── Spinning AI loader ── */
    .ai-loading {
        display: none;
        font-size: 0.82rem;
        color: #5a8f65;
        margin-top: 8px;
        align-items: center;
        gap: 7px;
    }

    .ai-loading.show {
        display: flex;
    }

    .ai-spinner {
        width: 14px; height: 14px;
        border: 2px solid #c5e8cc;
        border-top-color: #28a745;
        border-radius: 50%;
        animation: spin 0.7s linear infinite;
        flex-shrink: 0;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* ── Responsive ── */
    @media (max-width: 576px) {
        .create-body { padding: 24px 18px; }
        .create-header { padding: 24px 20px; }
        .create-header h4 { font-size: 1.3rem; }
    }
</style>

<div class="create-wrapper">

    <div class="create-card card">

        {{-- ── HEADER ── --}}
        <div class="create-header">
            <div class="d-flex align-items-center gap-3 text-white">
                <div style="width:52px;height:52px;border-radius:14px;background:rgba(255,255,255,0.18);display:flex;align-items:center;justify-content:center;font-size:1.7rem;backdrop-filter:blur(4px);">
                    🌾
                </div>
                <div>
                    <h4 class="mb-0">Add New Product</h4>
                    <p class="mb-0 mt-1">Fill in the details to list your farm product on AgriConnect</p>
                </div>
            </div>
        </div>

        {{-- ── BODY ── --}}
        <div class="create-body">

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="alert-validation">
                    <strong>⚠️ Please fix the following errors:</strong>
                    <ul class="mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Session Success --}}
            @if (session('success'))
                <div class="alert-success-custom">
                    ✅ {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" id="createProductForm">
                @csrf

                {{-- ── SECTION: Basic Info ── --}}
                <div class="form-section-title">📋 Basic Information</div>

                <div class="row g-3 mb-3">

                    {{-- Product Name --}}
                    <div class="col-12">
                        <label class="form-label" for="product_name">
                            Product Name <span class="required-star">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">📦</span>
                            <input type="text"
                                   name="product_name"
                                   id="product_name"
                                   class="form-control @error('product_name') is-invalid @enderror"
                                   placeholder="e.g. Fresh Tomatoes, Organic Wheat"
                                   value="{{ old('product_name') }}"
                                   required
                                   autocomplete="off"
                                   onkeyup="debounceCheckPrice()">
                        </div>
                        @error('product_name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Category --}}
                    <div class="col-12">
                        <label class="form-label" for="category">
                            Category <span class="required-star">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">🏷️</span>
                            <select name="category" id="category"
                                    class="form-select @error('category') is-invalid @enderror"
                                    required>
                                <option value="">— Select a category —</option>
                                <option value="Vegetables"      {{ old('category') == 'Vegetables'      ? 'selected' : '' }}>🥦 Vegetables</option>
                                <option value="Fruits"          {{ old('category') == 'Fruits'          ? 'selected' : '' }}>🍎 Fruits</option>
                                <option value="Dairy Products"  {{ old('category') == 'Dairy Products'  ? 'selected' : '' }}>🥛 Dairy Products</option>
                                <option value="Animal Products" {{ old('category') == 'Animal Products' ? 'selected' : '' }}>🐄 Animal Products</option>
                                <option value="Cash Crop"       {{ old('category') == 'Cash Crop'       ? 'selected' : '' }}>🌾 Cash Crop</option>
                            </select>
                        </div>
                        @error('category')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="col-12">
                        <label class="form-label" for="description">Description</label>
                        <textarea name="description"
                                  id="description"
                                  class="form-control @error('description') is-invalid @enderror"
                                  rows="3"
                                  placeholder="Describe freshness, harvest date, origin, or any special notes…">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                {{-- ── SECTION: Pricing ── --}}
                <div class="form-section-title">💰 Pricing & Availability</div>

                {{-- AI Suggestion --}}
                <div id="aiLoading" class="ai-loading mb-2">
                    <span class="ai-spinner"></span>
                    Fetching AI market price suggestion…
                </div>

                <div id="aiResult" class="ai-suggestion-box mb-3">
                    <span class="ai-badge">🤖 AI Market Insight</span>
                    <div class="ai-stats" id="aiStats"></div>
                </div>

                <div class="row g-3 mb-3">

                    {{-- Price --}}
                    <div class="col-sm-6">
                        <label class="form-label" for="price">
                            Price (₹) <span class="required-star">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number"
                                   name="price"
                                   id="price"
                                   class="form-control @error('price') is-invalid @enderror"
                                   placeholder="0.00"
                                   min="0"
                                   step="0.01"
                                   value="{{ old('price') }}"
                                   required>
                        </div>
                        @error('price')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Quantity --}}
                    <div class="col-sm-6">
                        <label class="form-label" for="quantity">
                            Quantity <span class="required-star">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">🔢</span>
                            <input type="number"
                                   name="quantity"
                                   id="quantity"
                                   class="form-control @error('quantity') is-invalid @enderror"
                                   placeholder="e.g. 50"
                                   min="1"
                                   value="{{ old('quantity') }}"
                                   required>
                        </div>
                        @error('quantity')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Unit --}}
                    <div class="col-sm-6">
                        <label class="form-label" for="unit">Unit</label>
                        <div class="input-group">
                            <span class="input-group-text">⚖️</span>
                            <select name="unit" id="unit" class="form-select">
                                <option {{ old('unit') == 'Kg'    ? 'selected' : '' }}>Kg</option>
                                <option {{ old('unit') == 'Liter' ? 'selected' : '' }}>Liter</option>
                                <option {{ old('unit') == 'Piece' ? 'selected' : '' }}>Piece</option>
                                <option {{ old('unit') == 'Dozen' ? 'selected' : '' }}>Dozen</option>
                                <option {{ old('unit') == 'Quintal' ? 'selected' : '' }}>Quintal</option>
                            </select>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="col-sm-6">
                        <label class="form-label" for="status">Status</label>
                        <div class="input-group">
                            <span class="input-group-text">🟢</span>
                            <select name="status" id="status" class="form-select">
                                <option value="available"   {{ old('status') == 'available'   ? 'selected' : '' }}>✅ Available</option>
                                <option value="unavailable" {{ old('status') == 'unavailable' ? 'selected' : '' }}>❌ Unavailable</option>
                            </select>
                        </div>
                    </div>

                </div>

                {{-- ── SECTION: Image ── --}}
                <div class="form-section-title">📷 Product Image</div>

                <div class="image-upload-area" id="uploadArea">
                    <input type="file"
                           name="image"
                           id="imageInput"
                           accept="image/*"
                           onchange="previewImage(event)">
                    <i class="bi bi-cloud-arrow-up upload-icon"></i>
                    <div class="upload-text">Click or drag & drop to upload an image</div>
                    <div class="text-muted" style="font-size:0.78rem;margin-top:4px;">JPG, PNG, WEBP — max 2 MB</div>
                </div>

                <div class="preview-container" id="previewContainer">
                    <img id="preview" src="" alt="Product preview">
                    <button type="button" class="remove-preview" onclick="removePreview()" title="Remove image">✕</button>
                </div>

                {{-- ── BUTTONS ── --}}
                <div class="d-flex justify-content-between align-items-center mt-4 pt-2" style="border-top:1.5px solid #e6f4ea;">
                    <a href="{{ route('farmer.dashboard') }}" class="btn btn-back">
                        <i class="bi bi-arrow-left me-1"></i> Back
                    </a>
                    <button type="submit" class="btn btn-submit" id="submitBtn">
                        <i class="bi bi-plus-circle me-2"></i> Add Product
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

{{-- ── SCRIPTS ── --}}
<script>

    /* Image preview */
    function previewImage(event) {
        const file = event.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
            document.getElementById('previewContainer').style.display = 'block';
            document.getElementById('uploadArea').style.borderColor = '#28a745';
        };
        reader.readAsDataURL(file);
    }

    function removePreview() {
        document.getElementById('imageInput').value = '';
        document.getElementById('preview').src = '';
        document.getElementById('previewContainer').style.display = 'none';
        document.getElementById('uploadArea').style.borderColor = '';
    }

    /* Debounced AI price check */
    let priceTimer = null;

    function debounceCheckPrice() {
        clearTimeout(priceTimer);
        priceTimer = setTimeout(checkPrice, 600);
    }

    function checkPrice() {
        const product = document.getElementById('product_name').value.trim();
        if (product.length < 2) return;

        const loadingEl = document.getElementById('aiLoading');
        const resultEl  = document.getElementById('aiResult');

        loadingEl.classList.add('show');
        resultEl.classList.remove('show');

        fetch('/ai-price?product_name=' + encodeURIComponent(product))
            .then(res => res.json())
            .then(data => {
                loadingEl.classList.remove('show');

                document.getElementById('aiStats').innerHTML = `
                    <div class="ai-stat">
                        <span class="ai-stat-label">Suggested Price</span>
                        <span class="ai-stat-value">₹${data.suggested_price}</span>
                    </div>
                    <div class="ai-stat">
                        <span class="ai-stat-label">Market Demand</span>
                        <span class="ai-stat-value">${data.demand}</span>
                    </div>
                    <div class="ai-stat">
                        <span class="ai-stat-label">Recommended Range</span>
                        <span class="ai-stat-value">₹${data.range_min} – ₹${data.range_max}</span>
                    </div>
                `;

                resultEl.classList.add('show');

                // Auto-fill price only if empty
                const priceInput = document.getElementById('price');
                if (!priceInput.value) {
                    priceInput.value = data.suggested_price;
                }
            })
            .catch(() => {
                loadingEl.classList.remove('show');
            });
    }

    /* Submit button loading state */
    document.getElementById('createProductForm').addEventListener('submit', function() {
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Saving…';
    });

</script>

@endsection
