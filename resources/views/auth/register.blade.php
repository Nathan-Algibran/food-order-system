{{--
 * Purpose: New user registration form
 * Used by: GET /register (AuthController@showRegister)
--}}
@extends('layouts.app')
@section('title', 'Daftar')

@section('content')

<style>
    /* Modern Register Page Styles */
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

    @keyframes checkBounce {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.2); }
    }

    .register-container {
        min-height: calc(100vh - 76px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 1.5rem;
        position: relative;
    }

    .register-wrapper {
        width: 100%;
        max-width: 480px;
        animation: floatIn 0.7s cubic-bezier(0.23, 1, 0.32, 1);
    }

    /* Register Card */
    .register-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(20px);
        border-radius: 32px;
        padding: 2.5rem;
        border: 1px solid rgba(255, 255, 255, 0.5);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
    }

    .register-card:hover {
        box-shadow: 0 30px 60px -15px rgba(245, 158, 11, 0.2);
        border-color: var(--amber);
    }

    /* Header */
    .register-header {
        text-align: center;
        margin-bottom: 2rem;
        animation: slideUp 0.5s ease 0.1s backwards;
    }

    .register-icon {
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

    .register-title {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        font-weight: 900;
        color: var(--char);
        margin-bottom: 0.5rem;
        letter-spacing: -0.02em;
    }

    .register-subtitle {
        color: var(--muted);
        font-size: 0.95rem;
    }

    /* Form Styles */
    .register-form {
        animation: slideUp 0.5s ease 0.2s backwards;
    }

    .form-group {
        margin-bottom: 1.3rem;
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

    .form-label .optional {
        color: var(--muted);
        font-weight: 400;
        font-size: 0.8rem;
        margin-left: auto;
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

    .form-input.success {
        border-color: #10b981;
        background: #f0fdf4;
    }

    textarea.form-input {
        resize: vertical;
        min-height: 80px;
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

    /* Password Strength */
    .password-strength {
        margin-top: 0.5rem;
    }

    .strength-bar {
        height: 4px;
        background: #e2e8f0;
        border-radius: 2px;
        overflow: hidden;
        margin-bottom: 0.25rem;
    }

    .strength-fill {
        height: 100%;
        width: 0%;
        transition: all 0.3s ease;
        border-radius: 2px;
    }

    .strength-weak { width: 33%; background: #ef4444; }
    .strength-medium { width: 66%; background: #f59e0b; }
    .strength-strong { width: 100%; background: #10b981; }

    .strength-text {
        font-size: 0.75rem;
        color: var(--muted);
    }

    /* Submit Button */
    .register-btn {
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
        margin-top: 0.5rem;
    }

    .register-btn::before {
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

    .register-btn:hover::before {
        width: 400px;
        height: 400px;
    }

    .register-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 30px -5px rgba(245, 158, 11, 0.5);
    }

    .register-btn:active {
        transform: translateY(-1px);
    }

    .register-btn i {
        font-size: 1.2rem;
        transition: transform 0.3s;
    }

    .register-btn:hover i {
        transform: rotate(5deg) scale(1.1);
    }

    .register-btn.loading {
        opacity: 0.8;
        pointer-events: none;
    }

    /* Login Link */
    .login-link {
        text-align: center;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid var(--border);
        animation: slideUp 0.5s ease 0.3s backwards;
    }

    .login-link p {
        color: var(--muted);
        font-size: 0.9rem;
    }

    .login-link a {
        color: var(--amber);
        text-decoration: none;
        font-weight: 700;
        margin-left: 0.3rem;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .login-link a:hover {
        color: var(--amber-dk);
        transform: translateX(3px);
    }

    .login-link a i {
        font-size: 1rem;
        transition: transform 0.3s;
    }

    .login-link a:hover i {
        transform: translateX(3px);
    }

    /* Benefits List */
    .benefits-list {
        display: flex;
        justify-content: center;
        gap: 1.5rem;
        margin-top: 1rem;
        animation: slideUp 0.5s ease 0.35s backwards;
    }

    .benefit-item {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.75rem;
        color: var(--muted);
    }

    .benefit-item i {
        color: #10b981;
        font-size: 1rem;
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
        .register-card {
            padding: 1.8rem;
        }

        .register-title {
            font-size: 1.8rem;
        }

        .register-icon {
            width: 70px;
            height: 70px;
            font-size: 2rem;
        }

        .benefits-list {
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
        }
    }
</style>

<div class="register-container">
    <div class="register-wrapper">
        {{-- Register Card --}}
        <div class="register-card">
            {{-- Header --}}
            <div class="register-header">
                <div class="register-icon">
                    🎉
                </div>
                <h1 class="register-title">Buat Akun Baru</h1>
                <p class="register-subtitle">Bergabung dan mulai pesan makanan favoritmu</p>
            </div>

            {{-- Register Form --}}
            <form method="POST" action="{{ route('register') }}" class="register-form" x-data="{ 
                loading: false,
                password: '',
                confirmPassword: '',
                passwordStrength: 0,
                checkPasswordStrength() {
                    let strength = 0;
                    if (this.password.length >= 6) strength++;
                    if (this.password.match(/[a-z]/) && this.password.match(/[A-Z]/)) strength++;
                    if (this.password.match(/[0-9]/)) strength++;
                    if (this.password.match(/[^a-zA-Z0-9]/)) strength++;
                    this.passwordStrength = strength;
                },
                getStrengthClass() {
                    if (this.passwordStrength <= 1) return 'strength-weak';
                    if (this.passwordStrength <= 3) return 'strength-medium';
                    return 'strength-strong';
                },
                getStrengthText() {
                    if (this.passwordStrength <= 1) return 'Lemah';
                    if (this.passwordStrength <= 3) return 'Sedang';
                    return 'Kuat';
                }
            }" @submit="loading = true">
                @csrf

                {{-- Nama --}}
                <div class="form-group">
                    <label for="nama" class="form-label">
                        <i class="ri-user-line"></i>
                        Nama Lengkap
                    </label>
                    <div class="input-wrapper">
                        <input 
                            id="nama" 
                            type="text" 
                            name="nama" 
                            value="{{ old('nama') }}" 
                            required 
                            autofocus
                            class="form-input {{ $errors->has('nama') ? 'error' : '' }} {{ old('nama') && !$errors->has('nama') ? 'success' : '' }}"
                            placeholder="Masukkan nama lengkap"
                        >
                        @if(old('nama') && !$errors->has('nama'))
                        <i class="ri-check-line" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: #10b981; animation: checkBounce 0.3s ease;"></i>
                        @endif
                    </div>
                    @error('nama')
                    <div class="error-message">
                        <i class="ri-error-warning-line"></i>
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                {{-- Email --}}
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
                            class="form-input {{ $errors->has('email') ? 'error' : '' }} {{ old('email') && !$errors->has('email') ? 'success' : '' }}"
                            placeholder="nama@email.com"
                        >
                        @if(old('email') && !$errors->has('email'))
                        <i class="ri-check-line" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: #10b981;"></i>
                        @endif
                    </div>
                    @error('email')
                    <div class="error-message">
                        <i class="ri-error-warning-line"></i>
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                {{-- Alamat --}}
                <div class="form-group">
                    <label for="alamat" class="form-label">
                        <i class="ri-map-pin-line"></i>
                        Alamat
                        <span class="optional">Opsional</span>
                    </label>
                    <textarea 
                        id="alamat" 
                        name="alamat" 
                        rows="2"
                        class="form-input {{ $errors->has('alamat') ? 'error' : '' }}"
                        placeholder="Jl. Contoh No. 1, Kota"
                    >{{ old('alamat') }}</textarea>
                    @error('alamat')
                    <div class="error-message">
                        <i class="ri-error-warning-line"></i>
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="form-group">
                    <label for="password" class="form-label">
                        <i class="ri-lock-line"></i>
                        Password
                    </label>
                    <div class="input-wrapper" x-data="{ show: false }">
                        <input 
                            id="password" 
                            :type="show ? 'text' : 'password'" 
                            name="password" 
                            required
                            x-model="password"
                            @input="checkPasswordStrength()"
                            class="form-input {{ $errors->has('password') ? 'error' : '' }}"
                            placeholder="Minimal 6 karakter"
                        >
                        <button type="button" @click="show = !show" class="password-toggle">
                            <i class="ri-eye-line" x-show="!show"></i>
                            <i class="ri-eye-off-line" x-show="show"></i>
                        </button>
                    </div>
                    
                    {{-- Password Strength Indicator --}}
                    <div class="password-strength" x-show="password.length > 0">
                        <div class="strength-bar">
                            <div class="strength-fill" :class="getStrengthClass()"></div>
                        </div>
                        <div class="strength-text">
                            Kekuatan password: <span x-text="getStrengthText()" :style="'color: ' + (passwordStrength <= 1 ? '#ef4444' : passwordStrength <= 3 ? '#f59e0b' : '#10b981')"></span>
                        </div>
                    </div>

                    @error('password')
                    <div class="error-message">
                        <i class="ri-error-warning-line"></i>
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">
                        <i class="ri-lock-password-line"></i>
                        Konfirmasi Password
                    </label>
                    <div class="input-wrapper" x-data="{ show: false }">
                        <input 
                            id="password_confirmation" 
                            :type="show ? 'text' : 'password'" 
                            name="password_confirmation" 
                            required
                            x-model="confirmPassword"
                            class="form-input"
                            placeholder="Ulangi password"
                        >
                        <button type="button" @click="show = !show" class="password-toggle">
                            <i class="ri-eye-line" x-show="!show"></i>
                            <i class="ri-eye-off-line" x-show="show"></i>
                        </button>
                    </div>
                    <div x-show="password && confirmPassword && password !== confirmPassword" class="error-message">
                        <i class="ri-error-warning-line"></i>
                        Password tidak cocok
                    </div>
                </div>

                {{-- Submit Button --}}
                <button type="submit" class="register-btn" :class="{ 'loading': loading }" :disabled="password && confirmPassword && password !== confirmPassword">
                    <template x-if="!loading">
                        <i class="ri-user-add-line"></i>
                    </template>
                    <template x-if="loading">
                        <span class="spinner"></span>
                    </template>
                    <span x-text="loading ? 'Membuat akun...' : 'Buat Akun'"></span>
                    <i class="ri-arrow-right-line" x-show="!loading"></i>
                </button>
            </form>

            {{-- Benefits --}}
            <div class="benefits-list">
                <div class="benefit-item">
                    <i class="ri-checkbox-circle-fill"></i>
                    <span>Gratis ongkir</span>
                </div>
                <div class="benefit-item">
                    <i class="ri-checkbox-circle-fill"></i>
                    <span>Diskon member</span>
                </div>
                <div class="benefit-item">
                    <i class="ri-checkbox-circle-fill"></i>
                    <span>Tracking real-time</span>
                </div>
            </div>

            {{-- Login Link --}}
            <div class="login-link">
                <p>
                    Sudah punya akun?
                    <a href="{{ route('login') }}">
                        Masuk di sini
                        <i class="ri-arrow-right-line"></i>
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
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