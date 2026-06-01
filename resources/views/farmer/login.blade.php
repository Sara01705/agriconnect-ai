@extends('layouts.app')

@section('content')

<style>
/* Center the card beautifully */
.login-page-container {
    min-height: 80vh;
    display: flex;
    justify-content: center;
    align-items: center;
    perspective: 1000px;
}

.login-glass-card {
    width: 100%;
    max-width: 440px;
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
    margin-bottom: 1.5rem;
}

.password-toggle-eye {
    position: absolute;
    right: 1.25rem;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    font-size: 1.1rem;
    color: #6b7280;
    z-index: 10;
    transition: color 0.2s;
}

.password-toggle-eye:hover {
    color: #28a745;
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

<div class="login-page-container">
    <div class="glass-card login-glass-card">
        
        <div class="auth-header">
            <div class="auth-header-icon">🌾</div>
            <h3 class="glass-title-green mb-1">Farmer Login</h3>
            <p class="text-muted small">Sign in to manage your fresh crops & orders</p>
        </div>

        <form method="POST" action="{{ route('farmer.login.submit') }}">
            @csrf

            <!-- Email Address -->
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
                           autocomplete="email" 
                           autofocus>
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
                           required 
                           autocomplete="current-password">
                    <span onclick="togglePasswordVisibility()" class="password-toggle-eye" id="toggleIcon">👁️</span>
                </div>
            </div>

            <button type="submit" class="glass-btn-green w-100 mt-2 mb-3">
                <i class="bi bi-box-arrow-in-right me-2"></i> Farmer Sign In
            </button>

            <p class="text-center text-muted small mb-0">
                New Farmer? <a href="{{ route('farmer.register') }}" class="auth-link">Register Now</a>
            </p>

        </form>
    </div>
</div>

<script>
function togglePasswordVisibility() {
    const passwordInput = document.getElementById("password");
    const toggleIcon = document.getElementById("toggleIcon");

    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        toggleIcon.textContent = "🙈";
    } else {
        passwordInput.type = "password";
        toggleIcon.textContent = "👁️";
    }
}
</script>

@endsection