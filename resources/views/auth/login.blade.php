@extends('layouts.app')

@section('content')
<head>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<div class="auth-container">
    <div class="auth-background">
        <div class="auth-shape shape-1"></div>
        <div class="auth-shape shape-2"></div>
        <div class="auth-shape shape-3"></div>
        <div class="floating-element el-1">
            <i class="fas fa-shipping-fast"></i>
        </div>
        <div class="floating-element el-2">
            <i class="fas fa-box"></i>
        </div>
        <div class="floating-element el-3">
            <i class="fas fa-truck"></i>
        </div>
    </div>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="auth-card animate-on-scroll">
                    <div class="card-header-wave">
                        <div class="wave"></div>
                        <div class="wave"></div>
                        <div class="wave"></div>
                    </div>

                    <div class="card-body p-5">
                        <div class="text-center mb-5">
                            <div class="auth-icon animate-icon">
                                <i class="fas fa-sign-in-alt"></i>
                            </div>
                            <h2 class="auth-title">مرحباً بعودتك</h2>
                            <p class="auth-subtitle">سجل الدخول إلى حسابك للمتابعة</p>
                        </div>

                        <form method="POST" action="{{ route('login') }}" class="auth-form">
                            @csrf

                            <div class="form-group-animated mb-4">
                                <div class="input-container">
                                    <input id="email" type="email" class="form-input @error('email') error @enderror"
                                           name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                    <label for="email" class="input-label">
                                        <i class="fas fa-envelope input-icon"></i>
                                        البريد الإلكتروني
                                    </label>
                                    <div class="input-underline"></div>
                                </div>
                                @error('email')
                                    <div class="error-message animate-error">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group-animated mb-4">
                                <div class="input-container">
                                    <input id="password" type="password" class="form-input @error('password') error @enderror"
                                           name="password" required autocomplete="current-password">
                                    <label for="password" class="input-label">
                                        <i class="fas fa-lock input-icon"></i>
                                        كلمة المرور
                                    </label>
                                    <div class="input-underline"></div>
                                    <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="error-message animate-error">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check remember-me">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                                        تذكرني
                                    </label>
                                </div>

                                @if (Route::has('password.request'))
                                    <a class="forgot-password" href="{{ route('password.request') }}">
                                        نسيت كلمة المرور؟
                                    </a>
                                @endif
                            </div>

                            <button type="submit" class="auth-btn btn-hover-effect">
                                <span class="btn-text">تسجيل الدخول</span>
                                <div class="btn-loader">
                                    <div class="loader-dot"></div>
                                    <div class="loader-dot"></div>
                                    <div class="loader-dot"></div>
                                </div>
                                <i class="fas fa-arrow-left btn-icon"></i>
                            </button>

                            <div class="auth-divider">
                                <span>أو</span>
                            </div>

                            <div class="social-auth">
                                <button type="button" class="social-btn google-btn">
                                    <i class="fab fa-google"></i>
                                    متابعة مع جوجل
                                </button>
                                <button type="button" class="social-btn facebook-btn">
                                    <i class="fab fa-facebook-f"></i>
                                    متابعة مع فيسبوك
                                </button>
                            </div>

                            <div class="auth-footer text-center mt-4">
                                <p>ليس لديك حساب؟
                                    <a href="{{ route('register') }}" class="auth-link">
                                        إنشاء حساب جديد
                                        <i class="fas fa-arrow-left"></i>
                                    </a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="{{ asset('js/login.js') }}"></script>

@endsection
