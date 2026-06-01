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
    background: rgba(220, 38, 38, 0.08);
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    color: #dc2626;
    margin-bottom: 0.75rem;
    box-shadow: 0 4px 12px rgba(220, 38, 38, 0.05);
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
    color: #dc2626;
}
</style>

<div class="login-page-container">
    <div class="glass-card login-glass-card">
        
        <div class="auth-header">
            <div class="auth-header-icon">🛡️</div>
            <h3 class="glass-title-red mb-1">Admin Portal</h3>
            <p class="text-muted small">Sign in to access secure system control console</p>
        </div>

        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf

            <!-- Email Address -->
            <div class="form-group-custom">
                <label class="glass-label" for="email">Administrator Email</label>
                <div style="position: relative;">
                    <span class="glass-input-icon">📧</span>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           class="form-control glass-input glass-input-with-icon w-100" 
                           placeholder="admin@agriconnect.com" 
                           value="{{ old('email') }}" 
                           required 
                           autocomplete="email" 
                           autofocus>
                </div>
            </div>

            <!-- Password -->
            <div class="form-group-custom">
                <label class="glass-label" for="password">Security Password</label>
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

            <button type="submit" class="glass-btn-danger w-100 mt-2 mb-2">
                <i class="bi bi-shield-lock me-2"></i> Authenticate & Enter
            </button>

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