{{--
 * Purpose: User login form
 * Used by: GET /login (AuthController@showLogin)
--}}
@extends('layouts.app')
@section('title', 'Masuk')

@section('content')

<style>
    /* Modern Login Page Styles */
    @keyframes floatIn {
        0% { opacity: 0; transform: translateY(30px) scale(0.95); }
        100% { opacity: 1; transform: translateY(0) scale(1); }
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes pulseGlow {
        0%, 100% { box-shadow: 0 0 20px rgba(245,158,11,0.2); }
        50% { box-shadow: 0 0 30px rgba(245,158,11,0.4); }
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }

    .login-container {
        min-height: calc(100vh - 76px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 1.5rem;
        position: relative;
    }

    .login-wrapper {
        width: 100%;
        max-width: 440px;
        animation: floatIn 0.7s cubic-bezier(0.23, 1, 0.32, 1);
    }

    /* Login Card */
    .login-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(20px);
        border-radius: 32px;
        padding: 2.5rem;
        border: 1px solid rgba(255, 255, 255, 0.5);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
    }

    .login-card:hover {
        box-shadow: 0 30px 60px -15px rgba(245, 158, 11, 0.2);
        border-color: var(--amber);
    }

    /* Header */
    .login-header {
        text-align: center;
        margin-bottom: 2rem;
        animation: slideUp 0.5s ease 0.1s backwards;
    }

    .login-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--amber-lt), #fff);
        border-radius: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 2.5rem;
        box-shadow: 0 10px 25px -5px rgba(245, 158, 11, 0.2);
        animation: pulseGlow 3s infinite;
        border: 2px solid rgba(245, 158, 11, 0.2);
    }

    .login-title {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        font-weight: 900;
        color: var(--char);
        margin-bottom: 0.5rem;
        letter-spacing: -0.02em;
    }

    .login-subtitle {
        color: var(--muted);
        font-size: 0.95rem;
    }

    /* Form Styles */
    .login-form {
        animation: slideUp 0.5s ease 0.2s backwards;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--char);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-label i {
        color: var(--amber);
        font-size: 1.1rem;
    }

    .input-wrapper {
        position: relative;
    }

    .form-input {
        width: 100%;
        padding: 0.9rem 1rem;
        background: rgba(255, 255, 255, 0.6);
        border: 2px solid var(--border);
        border-radius: 16px;
        font-size: 0.95rem;
        color: var(--char);
        transition: all 0.3s cubic-bezier(0.23, 1, 0.32, 1);
        outline: none;
    }

    .form-input:focus {
        background: white;
        border-color: var(--amber);
        box-shadow: 0 4px 15px rgba(245, 158, 11, 0.1);
        transform: translateY(-2px);
    }

    .form-input.error {
        border-color: #ef4444;
        background: #fef2f2;
        animation: shake 0.4s ease;
    }

    .password-toggle {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--muted);
        cursor: pointer;
        padding: 0.25rem;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .password-toggle:hover {
        color: var(--amber);
    }

    .error-message {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        margin-top: 0.5rem;
        font-size: 0.8rem;
        color: #ef4444;
        animation: slideUp 0.3s ease;
    }

    .error-message i {
        font-size: 1rem;
    }

    /* Remember Me */
    .remember-wrapper {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
    }

    .checkbox-wrapper {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
    }

    .checkbox-wrapper input[type="checkbox"] {
        width: 1.2rem;
        height: 1.2rem;
        border-radius: 6px;
        border: 2px solid var(--border);
        cursor: pointer;
        accent-color: var(--amber);
    }

    .checkbox-label {
        font-size: 0.9rem;
        color: var(--muted);
        cursor: pointer;
    }

    .forgot-link {
        font-size: 0.85rem;
        color: var(--amber);
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s;
    }

    .forgot-link:hover {
        color: var(--amber-dk);
        text-decoration: underline;
    }

    /* Submit Button */
    .login-btn {
        width: 100%;
        padding: 1rem;
        background: linear-gradient(135deg, var(--amber), var(--amber-dk));
        color: white;
        border: none;
        border-radius: 16px;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.23, 1, 0.32, 1);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.8rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 20px -5px rgba(245, 158, 11, 0.4);
    }

    .login-btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }

    .login-btn:hover::before {
        width: 400px;
        height: 400px;
    }

    .login-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 30px -5px rgba(245, 158, 11, 0.5);
    }

    .login-btn:active {
        transform: translateY(-1px);
    }

    .login-btn i {
        font-size: 1.2rem;
        transition: transform 0.3s;
    }

    .login-btn:hover i {
        transform: translateX(5px);
    }

    .login-btn.loading {
        opacity: 0.8;
        pointer-events: none;
    }

    /* Register Link */
    .register-link {
        text-align: center;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--border);
        animation: slideUp 0.5s ease 0.3s backwards;
    }

    .register-link p {
        color: var(--muted);
        font-size: 0.9rem;
    }

    .register-link a {
        color: var(--amber);
        text-decoration: none;
        font-weight: 700;
        margin-left: 0.3rem;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .register-link a:hover {
        color: var(--amber-dk);
        transform: translateX(3px);
    }

    .register-link a i {
        font-size: 1rem;
        transition: transform 0.3s;
    }

    .register-link a:hover i {
        transform: translateX(3px);
    }

    /* Demo Credentials */
    .demo-credentials {
        margin-top: 1rem;
        padding: 0.8rem;
        background: rgba(254, 243, 199, 0.3);
        border-radius: 12px;
        text-align: center;
        animation: slideUp 0.5s ease 0.35s backwards;
    }

    .demo-credentials p {
        font-size: 0.8rem;
        color: var(--muted);
        margin-bottom: 0.3rem;
    }

    .demo-credentials code {
        background: white;
        padding: 0.2rem 0.6rem;
        border-radius: 6px;
        font-size: 0.8rem;
        color: var(--amber-dk);
        margin: 0 0.2rem;
    }

    /* Spinner */
    .spinner {
        width: 20px;
        height: 20px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 50%;
        border-top-color: white;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Responsive */
    @media (max-width: 480px) {
        .login-card {
            padding: 1.8rem;
        }

        .login-title {
            font-size: 1.8rem;
        }

        .login-icon {
            width: 70px;
            height: 70px;
            font-size: 2rem;
        }

        .remember-wrapper {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.8rem;
        }
    }
</style>

<div class="login-container">
    <div class="login-wrapper">
        {{-- Login Card --}}
        <div class="login-card">
            {{-- Header --}}
            <div class="login-header">
                <div class="login-icon">
                    🍜
                </div>
                <h1 class="login-title">Selamat Datang</h1>
                <p class="login-subtitle">Masuk untuk melanjutkan memesan</p>
            </div>

            {{-- Login Form --}}
            <form method="POST" action="{{ route('login') }}" class="login-form" x-data="{ loading: false }" @submit="loading = true">
                @csrf

                {{-- Email Field --}}
                <div class="form-group">
                    <label for="email" class="form-label">
                        <i class="ri-mail-line"></i>
                        Email
                    </label>
                    <div class="input-wrapper">
                        <input 
                            id="email" 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            autofocus
                            class="form-input {{ $errors->has('email') ? 'error' : '' }}"
                            placeholder="nama@email.com"
                        >
                    </div>
                    @error('email')
                    <div class="error-message">
                        <i class="ri-error-warning-line"></i>
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                {{-- Password Field --}}
                <div class="form-group" x-data="{ show: false }">
                    <label for="password" class="form-label">
                        <i class="ri-lock-line"></i>
                        Password
                    </label>
                    <div class="input-wrapper">
                        <input 
                            id="password" 
                            :type="show ? 'text' : 'password'" 
                            name="password" 
                            required
                            class="form-input"
                            placeholder="••••••••"
                        >
                        <button type="button" @click="show = !show" class="password-toggle">
                            <i class="ri-eye-line" x-show="!show"></i>
                            <i class="ri-eye-off-line" x-show="show"></i>
                        </button>
                    </div>
                </div>

                {{-- Remember Me & Forgot Password --}}
                <div class="remember-wrapper">
                    <label class="checkbox-wrapper">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span class="checkbox-label">Ingat saya</span>
                    </label>
                    <a href="#" class="forgot-link">
                        <i class="ri-question-line"></i> Lupa password?
                    </a>
                </div>

                {{-- Submit Button --}}
                <button type="submit" class="login-btn" :class="{ 'loading': loading }">
                    <template x-if="!loading">
                        <i class="ri-login-box-line"></i>
                    </template>
                    <template x-if="loading">
                        <span class="spinner"></span>
                    </template>
                    <span x-text="loading ? 'Memproses...' : 'Masuk'"></span>
                    <i class="ri-arrow-right-line" x-show="!loading"></i>
                </button>
            </form>

            {{-- Register Link --}}
            <div class="register-link">
                <p>
                    Belum punya akun?
                    <a href="{{ route('register') }}">
                        Daftar sekarang
                        <i class="ri-arrow-right-line"></i>
                    </a>
                </p>
            </div>

            {{-- Demo Credentials (Optional - untuk testing) --}}
            <div class="demo-credentials" x-data="{ showDemo: true }" x-show="showDemo">
                <p>
                    <i class="ri-information-line"></i> Demo: 
                    <code>user@example.com</code> / 
                    <code>password</code>
                </p>
                <button type="button" @click="showDemo = false" style="background: none; border: none; color: var(--muted); font-size: 0.7rem; cursor: pointer; margin-top: 0.3rem;">
                    <i class="ri-close-line"></i> Sembunyikan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Auto-fill demo credentials jika ada (untuk testing)
        const demoBtn = document.querySelector('[data-demo]');
        if (demoBtn) {
            demoBtn.addEventListener('click', (e) => {
                e.preventDefault();
                document.getElementById('email').value = 'user@example.com';
                document.getElementById('password').value = 'password';
            });
        }

        // Clear error on input
        const inputs = document.querySelectorAll('.form-input');
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                this.classList.remove('error');
                const errorMsg = this.closest('.form-group')?.querySelector('.error-message');
                if (errorMsg) {
                    errorMsg.style.display = 'none';
                }
            });
        });
    });
</script>

@endsection