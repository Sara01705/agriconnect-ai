@extends('layouts.app')

@section('content')

<style>
/* Center the card beautifully */
.register-page-container {
    min-height: 85vh;
    display: flex;
    justify-content: center;
    align-items: center;
    perspective: 1000px;
    padding: 30px 0;
}

.register-glass-card {
    width: 100%;
    max-width: 480px;
    margin: 20px;
}

.auth-header {
    text-align: center;
    margin-bottom: 1.75rem;
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
            <div class="auth-header-icon">🌱</div>
            <h3 class="glass-title-green mb-1">Create Account</h3>
            <p class="text-muted small">Register to start ordering fresh crops & products</p>
        </div>

        <form method="POST" action="{{ route('user.register.submit') }}">
            @csrf

            <!-- Name -->
            <div class="form-group-custom">
                <label class="glass-label" for="name">Full Name</label>
                <div style="position: relative;">
                    <span class="glass-input-icon">👤</span>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           class="form-control glass-input glass-input-with-icon w-100" 
                           placeholder="John Doe" 
                           value="{{ old('name') }}" 
                           required 
                           autocomplete="name" 
                           autofocus>
                </div>
            </div>

            <!-- Email -->
            <div class="form-group-custom">
                <label class="glass-label" for="email">Email Address</label>
                <div style="position: relative;">
                    <span class="glass-input-icon">📧</span>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           class="form-control glass-input glass-input-with-icon w-100" 
                           placeholder="john@example.com" 
                           value="{{ old('email') }}" 
                           required 
                           autocomplete="email">
                </div>
            </div>

            <!-- Phone Number -->
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

            <!-- Address -->
            <div class="form-group-custom">
                <label class="glass-label" for="address">Address</label>
                <div style="position: relative;">
                    <span class="glass-input-icon">🏠</span>
                    <input type="text" 
                           id="address" 
                           name="address" 
                           class="form-control glass-input glass-input-with-icon w-100" 
                           placeholder="123 Farm Lane" 
                           value="{{ old('address') }}" 
                           required>
                </div>
            </div>

            <!-- District -->
            <div class="form-group-custom">
                <label class="glass-label" for="district">District</label>
                <div style="position: relative;">
                    <span class="glass-input-icon"></span>
                    <input type="text" 
                           id="district" 
                           name="district" 
                           class="form-control glass-input glass-input-with-icon w-100" 
                           placeholder="Coimbatore" 
                           value="{{ old('district') }}" 
                           required>
                </div>
            </div>

            <!-- State -->
            <div class="form-group-custom">
                <label class="glass-label" for="state">State</label>
                <div style="position: relative;">
                    <span class="glass-input-icon"></span>
                    <input type="text" 
                           id="state" 
                           name="state" 
                           class="form-control glass-input glass-input-with-icon w-100" 
                           placeholder="Tamil Nadu" 
                           value="{{ old('state') }}" 
                           required>
                </div>
            </div>

            <!-- Password -->
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

            <button type="submit" class="glass-btn-green w-100 mt-3 mb-3">
                <i class="bi bi-person-plus me-2"></i> Register Account
            </button>

            <div class="d-flex justify-content-between align-items-center mt-2 pt-2" style="border-top: 1px solid rgba(40, 167, 69, 0.15);">
                <a href="{{ route('user.login') }}" class="glass-btn-secondary text-decoration-none">
                    <i class="bi bi-arrow-left me-1"></i> Back to Login
                </a>
                <span class="text-muted small">
                    Already registered? <a href="{{ route('user.login') }}" class="auth-link">Login</a>
                </span>
            </div>

        </form>
    </div>
</div>

@endsection
