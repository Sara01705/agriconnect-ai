@extends('layouts.app')

@section('content')

<style>
/* Center the card beautifully */
.register-page-container {
    min-height: 90vh;
    display: flex;
    justify-content: center;
    align-items: center;
    perspective: 1000px;
    padding: 40px 0;
}

.register-glass-card {
    width: 100%;
    max-width: 720px;
    margin: 20px;
}

.auth-header {
    text-align: center;
    margin-bottom: 2rem;
}

.auth-header-icon {
    width: 60px;
    height: 60px;
    background: rgba(40, 167, 69, 0.12);
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    color: #28a745;
    margin-bottom: 0.75rem;
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.08);
}

.form-group-custom {
    position: relative;
    margin-bottom: 1.25rem;
}

.auth-link {
    text-decoration: none;
    color: #28a745;
    font-weight: 700;
    transition: all 0.2s;
}

.auth-link:hover {
    color: #1e7e34;
    text-decoration: underline;
}
</style>

<div class="register-page-container">
    <div class="glass-card register-glass-card">
        
        <div class="auth-header">
            <div class="auth-header-icon">🚜</div>
            <h3 class="glass-title-green mb-1">Farmer Registration</h3>
            <p class="text-muted small">Register to list your organic products and accept buyer requests</p>
        </div>

        <form method="POST" action="{{ route('farmer.register.submit') }}">
            @csrf

            <div class="row">
                <!-- Name -->
                <div class="col-md-6">
                    <div class="form-group-custom">
                        <label class="glass-label" for="name">Full Name</label>
                        <div style="position: relative;">
                            <span class="glass-input-icon">👤</span>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   class="form-control glass-input glass-input-with-icon w-100" 
                                   placeholder="Farmer Name" 
                                   value="{{ old('name') }}" 
                                   required 
                                   autocomplete="name" 
                                   autofocus>
                        </div>
                    </div>
                </div>

                <!-- Email -->
                <div class="col-md-6">
                    <div class="form-group-custom">
                        <label class="glass-label" for="email">Email Address</label>
                        <div style="position: relative;">
                            <span class="glass-input-icon">📧</span>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   class="form-control glass-input glass-input-with-icon w-100" 
                                   placeholder="farmer@example.com" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autocomplete="email">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Phone -->
                <div class="col-md-6">
                    <div class="form-group-custom">
                        <label class="glass-label" for="phone">Phone Number</label>
                        <div style="position: relative;">
                            <span class="glass-input-icon">📞</span>
                            <input type="text" 
                                   id="phone" 
                                   name="phone" 
                                   class="form-control glass-input glass-input-with-icon w-100" 
                                   placeholder="9876543210" 
                                   value="{{ old('phone') }}" 
                                   required 
                                   autocomplete="tel">
                        </div>
                    </div>
                </div>

                <!-- Password -->
                <div class="col-md-6">
                    <div class="form-group-custom">
                        <label class="glass-label" for="password">Password</label>
                        <div style="position: relative;">
                            <span class="glass-input-icon">🔒</span>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   class="form-control glass-input glass-input-with-icon w-100" 
                                   placeholder="••••••••" 
                                   required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Address -->
            <div class="form-group-custom">
                <label class="glass-label" for="address">Farm Address</label>
                <div style="position: relative;">
                    <span class="glass-input-icon" style="top: 1.6rem; transform: none;">📍</span>
                    <textarea id="address" 
                              name="address" 
                              class="form-control glass-input glass-input-with-icon w-100" 
                              rows="2" 
                              placeholder="Farm No., Street Name, Village Name..." 
                              style="resize: none;">{{ old('address') }}</textarea>
                </div>
            </div>

            <div class="row">
                <!-- District -->
                <div class="col-md-6">
                    <div class="form-group-custom">
                        <label class="glass-label" for="district">District</label>
                        <div style="position: relative;">
                            <span class="glass-input-icon">🏘️</span>
                            <input type="text" 
                                   id="district" 
                                   name="district" 
                                   class="form-control glass-input glass-input-with-icon w-100" 
                                   placeholder="District Name" 
                                   value="{{ old('district') }}" 
                                   required>
                        </div>
                    </div>
                </div>

                <!-- State -->
                <div class="col-md-6">
                    <div class="form-group-custom">
                        <label class="glass-label" for="state">State</label>
                        <div style="position: relative;">
                            <span class="glass-input-icon">🗺️</span>
                            <input type="text" 
                                   id="state" 
                                   name="state" 
                                   class="form-control glass-input glass-input-with-icon w-100" 
                                   placeholder="State Name" 
                                   value="{{ old('state') }}" 
                                   required>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="glass-btn-green w-100 mt-3 mb-3">
                <i class="bi bi-patch-check me-2"></i> Register as Farmer
            </button>

            <div class="d-flex justify-content-between align-items-center mt-2 pt-2" style="border-top: 1px solid rgba(40, 167, 69, 0.15);">
                <a href="{{ route('farmer.login') }}" class="glass-btn-secondary text-decoration-none">
                    <i class="bi bi-arrow-left me-1"></i> Back to Login
                </a>
                <span class="text-muted small">
                    Already registered? <a href="{{ route('farmer.login') }}" class="auth-link">Login Here</a>
                </span>
            </div>

        </form>
    </div>
</div>

@endsection
