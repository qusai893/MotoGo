@extends('layouts.app')

@section('content')

    <head>
        <link rel="stylesheet" href="{{ asset('css/register.css') }}">
    </head>
    <div class="auth-container">
        <div class="auth-background">
            <div class="auth-shape shape-1"></div>
            <div class="auth-shape shape-2"></div>
            <div class="auth-shape shape-3"></div>
            <div class="floating-element el-1">
                <i class="fas fa-user-plus"></i>
            </div>
            <div class="floating-element el-2">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div class="floating-element el-3">
                <i class="fas fa-check-circle"></i>
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
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <h2 class="auth-title">انضم إلينا</h2>
                                <p class="auth-subtitle">أنشئ حسابك الجديد لتبدأ رحلتك معنا</p>
                            </div>

                            <form method="POST" action="{{ route('register') }}" class="auth-form" id="registerForm">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group-animated mb-4">
                                            <div class="input-container">
                                                <input id="name" type="text"
                                                    class="form-input @error('name') error @enderror" name="name"
                                                    value="{{ old('name') }}" required autocomplete="name" autofocus>
                                                <label for="name" class="input-label">
                                                    <i class="fas fa-user input-icon"></i>
                                                    الاسم الكامل
                                                </label>
                                                <div class="input-underline"></div>
                                            </div>
                                            @error('name')
                                                <div class="error-message animate-error">
                                                    <i class="fas fa-exclamation-circle"></i>
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group-animated mb-4">
                                            <div class="input-container">
                                                <input id="email" type="email"
                                                    class="form-input @error('email') error @enderror" name="email"
                                                    value="{{ old('email') }}" required autocomplete="email">
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
                                    </div>
                                </div>

                                <!-- Email Verification Section -->
                                <div class="form-group-animated mb-4" id="verification-section">
                                    <div class="input-container">
                                        <input id="verification_code" type="text"
                                            class="form-input @error('verification_code') error @enderror"
                                            name="verification_code" maxlength="6"
                                            placeholder="أدخل الرمز المكون من 6 أرقام">
                                        <label for="verification_code" class="input-label">
                                            <i class="fas fa-shield-alt input-icon"></i>
                                            رمز التحقق من البريد الإلكتروني
                                        </label>
                                        <div class="input-underline"></div>
                                    </div>

                                    <!-- Verification Buttons -->
                                    <div class="d-flex gap-2 mt-3">
                                        <button type="button" id="sendCodeBtn"
                                            class="btn btn-outline-primary btn-sm flex-fill">
                                            <i class="fas fa-paper-plane"></i>
                                            إرسال الرمز عبر البريد
                                        </button>
                                        <button type="button" id="verifyCodeBtn"
                                            class="btn btn-outline-success btn-sm flex-fill">
                                            <i class="fas fa-check"></i>
                                            تحقق من الرمز
                                        </button>
                                    </div>

                                    <!-- Verification Status -->
                                    <div id="verificationStatus" class="mt-2"></div>

                                    @error('verification_code')
                                        <div class="error-message animate-error">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group-animated mb-4">
                                            <div class="input-container">
                                                <input id="password" type="password"
                                                    class="form-input @error('password') error @enderror" name="password"
                                                    required autocomplete="new-password">
                                                <label for="password" class="input-label">
                                                    <i class="fas fa-lock input-icon"></i>
                                                    كلمة المرور
                                                </label>
                                                <div class="input-underline"></div>
                                                <button type="button" class="password-toggle"
                                                    onclick="togglePassword('password')">
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
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group-animated mb-4">
                                            <div class="input-container">
                                                <input id="password-confirm" type="password" class="form-input"
                                                    name="password_confirmation" required autocomplete="new-password">
                                                <label for="password-confirm" class="input-label">
                                                    <i class="fas fa-lock input-icon"></i>
                                                    تأكيد كلمة المرور
                                                </label>
                                                <div class="input-underline"></div>
                                                <button type="button" class="password-toggle"
                                                    onclick="togglePassword('password-confirm')">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="password-strength mb-4">
                                    <div class="strength-bar">
                                        <div class="strength-fill" data-strength="0"></div>
                                    </div>
                                    <div class="strength-text" data-text="قوة كلمة المرور"></div>
                                </div>

                                <div class="form-group-animated mb-4">
                                    <div class="terms-container">
                                        <input class="terms-checkbox @error('terms') error @enderror" type="checkbox"
                                            name="terms" id="terms" required>
                                        <label class="terms-label" for="terms">
                                            <span class="checkmark"></span>
                                            أوافق على <a href="#" class="terms-link">شروط الاستخدام</a> و <a
                                                href="#" class="terms-link">سياسة الخصوصية</a>
                                        </label>
                                    </div>
                                    @error('terms')
                                        <div class="error-message animate-error">
                                            <i class="fas fa-exclamation-circle"></i>
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <button type="submit" class="auth-btn btn-hover-effect" id="submitBtn" disabled>
                                    <span class="btn-text">إنشاء الحساب</span>
                                    <div class="btn-loader">
                                        <div class="loader-dot"></div>
                                        <div class="loader-dot"></div>
                                        <div class="loader-dot"></div>
                                    </div>
                                    <i class="fas fa-user-plus btn-icon"></i>
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
                                    <p>لديك حساب بالفعل؟
                                        <a href="{{ route('login') }}" class="auth-link">
                                            تسجيل الدخول
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

    <script src="{{ asset('js/register.js') }}"></script>
@endsection
