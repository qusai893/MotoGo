// public/js/register.js

// Email Verification Class
class EmailVerification {
    constructor() {
        this.sendCodeBtn = document.getElementById('sendCodeBtn');
        this.verifyCodeBtn = document.getElementById('verifyCodeBtn');
        this.verificationCodeInput = document.getElementById('verification_code');
        this.emailInput = document.getElementById('email');
        this.verificationStatus = document.getElementById('verificationStatus');
        this.submitBtn = document.getElementById('submitBtn');
        this.isVerified = false;

        this.init();
    }

    init() {
        this.sendCodeBtn.addEventListener('click', () => this.sendVerificationCode());
        this.verifyCodeBtn.addEventListener('click', () => this.verifyCode());

        // Email değiştiğinde doğrulamayı sıfırla
        this.emailInput.addEventListener('input', () => {
            this.resetVerification();
        });

        // Sayfa yüklendiğinde localStorage'dan doğrulama durumunu kontrol et
        this.checkStoredVerification();
    }

    checkStoredVerification() {
        const storedEmail = localStorage.getItem('verified_email');
        const currentEmail = this.emailInput.value.trim();

        if (storedEmail && storedEmail === currentEmail && localStorage.getItem('email_verified') === 'true') {
            this.isVerified = true;
            this.verifyCodeBtn.innerHTML = '<i class="fas fa-check-circle"></i> تم التحقق';
            this.verifyCodeBtn.classList.remove('btn-outline-success');
            this.verifyCodeBtn.classList.add('btn-success');
            this.submitBtn.disabled = false;
            this.showSuccess('تم التحقق مسبقاً');
        }
    }

    resetVerification() {
        this.isVerified = false;
        this.verificationCodeInput.value = '';
        this.submitBtn.disabled = true;
        this.verificationStatus.innerHTML = '';
        this.verifyCodeBtn.innerHTML = '<i class="fas fa-check"></i> تحقق من الرمز';
        this.verifyCodeBtn.classList.remove('btn-success');
        this.verifyCodeBtn.classList.add('btn-outline-success');

        // localStorage'ı temizle
        localStorage.removeItem('email_verified');
        localStorage.removeItem('verified_email');
    }

    async sendVerificationCode() {
        const email = this.emailInput.value.trim();

        if (!this.isValidEmail(email)) {
            this.showError('يرجى إدخال بريد إلكتروني صحيح');
            return;
        }

        console.log('📤 Sending code to:', email);

        this.sendCodeBtn.disabled = true;
        this.sendCodeBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الإرسال...';

        try {
            const response = await fetch('/send-verification-code', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    email: email
                })
            });

            const result = await response.json();

            if (result.success) {
                this.showSuccess(result.message);
                this.verificationCodeInput.focus();

                // Başarılı gönderimde localStorage'ı temizle (yeni kod gönderildi)
                localStorage.removeItem('email_verified');
                localStorage.removeItem('verified_email');
                this.isVerified = false;
                this.submitBtn.disabled = true;
            } else {
                this.showError(result.message);
            }
        } catch (error) {
            console.error('Send code error:', error);
            this.showError('حدث خطأ أثناء الإرسال: ' + error.message);
        } finally {
            this.sendCodeBtn.disabled = false;
            this.sendCodeBtn.innerHTML = '<i class="fas fa-paper-plane"></i> إرسال الرمز عبر البريد';
        }
    }

    async verifyCode() {
        const email = this.emailInput.value.trim();
        const code = this.verificationCodeInput.value.trim();

        if (!this.isValidEmail(email)) {
            this.showError('يرجى إدخال بريد إلكتروني صحيح');
            return;
        }

        if (code.length !== 6) {
            this.showError('يرجى إدخال رمز التحقق المكون من 6 أرقام');
            return;
        }

        console.log('🔍 Verifying code for:', email);

        this.verifyCodeBtn.disabled = true;
        this.verifyCodeBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التحقق...';

        try {
            const response = await fetch('/verify-code', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    email: email,
                    code: code
                })
            });

            const result = await response.json();

            if (result.success) {
                this.isVerified = true;
                this.showSuccess(result.message);
                this.verifyCodeBtn.innerHTML = '<i class="fas fa-check-circle"></i> تم التحقق';
                this.verifyCodeBtn.classList.remove('btn-outline-success');
                this.verifyCodeBtn.classList.add('btn-success');
                this.submitBtn.disabled = false;

                // Store verification status in localStorage
                localStorage.setItem('email_verified', 'true');
                localStorage.setItem('verified_email', email);

                console.log('✅ Email verified successfully');
            } else {
                this.showError(result.message);
            }
        } catch (error) {
            console.error('Verify code error:', error);
            this.showError('حدث خطأ أثناء التحقق: ' + error.message);
        } finally {
            this.verifyCodeBtn.disabled = false;
            if (!this.isVerified) {
                this.verifyCodeBtn.innerHTML = '<i class="fas fa-check"></i> تحقق من الرمز';
            }
        }
    }

    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    showSuccess(message) {
        this.clearMessages();
        const successDiv = document.createElement('div');
        successDiv.className = 'alert alert-success mt-2';
        successDiv.innerHTML = `<i class="fas fa-check-circle"></i> ${message}`;
        this.verificationStatus.appendChild(successDiv);

        setTimeout(() => successDiv.remove(), 5000);
    }

    showError(message) {
        this.clearMessages();
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message animate-error mt-2';
        errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
        this.verificationStatus.appendChild(errorDiv);

        setTimeout(() => errorDiv.remove(), 5000);
    }

    clearMessages() {
        this.verificationStatus.innerHTML = '';
    }
}

// Password toggle functionality
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = field.parentNode.querySelector('.password-toggle i');

    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Password strength checker
function checkPasswordStrength(password) {
    let strength = 0;
    const strengthBar = document.querySelector('.strength-fill');
    const strengthText = document.querySelector('.strength-text');

    if (!strengthBar || !strengthText) return;

    if (password.length >= 8) strength++;
    if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
    if (password.match(/\d/)) strength++;
    if (password.match(/[^a-zA-Z\d]/)) strength++;

    const strengthPercent = (strength / 4) * 100;
    strengthBar.style.width = strengthPercent + '%';

    switch (strength) {
        case 0:
            strengthBar.style.background = '#e1e5e9';
            strengthText.textContent = 'أدخل كلمة المرور';
            strengthText.style.color = '#6c757d';
            break;
        case 1:
            strengthBar.style.background = '#e74c3c';
            strengthText.textContent = 'ضعيفة';
            strengthText.style.color = '#e74c3c';
            break;
        case 2:
            strengthBar.style.background = '#f39c12';
            strengthText.textContent = 'متوسطة';
            strengthText.style.color = '#f39c12';
            break;
        case 3:
            strengthBar.style.background = '#3498db';
            strengthText.textContent = 'جيدة';
            strengthText.style.color = '#3498db';
            break;
        case 4:
            strengthBar.style.background = '#2ecc71';
            strengthText.textContent = 'قوية';
            strengthText.style.color = '#2ecc71';
            break;
    }
}

// Form submission handler
function handleFormSubmission() {
    const form = document.querySelector('.auth-form');
    if (!form) return;

    const submitBtn = form.querySelector('.auth-btn');
    const btnText = submitBtn.querySelector('.btn-text');
    const btnLoader = submitBtn.querySelector('.btn-loader');

    form.addEventListener('submit', function (e) {
        // Email doğrulama kontrolü
        if (!window.emailVerification.isVerified) {
            e.preventDefault();
            window.emailVerification.showError('يرجى التحقق من البريد الإلكتروني أولاً');
            return;
        }

        // Email'in doğrulanmış email ile aynı olup olmadığını kontrol et
        const verifiedEmail = localStorage.getItem('verified_email');
        const currentEmail = document.getElementById('email').value.trim();

        if (verifiedEmail !== currentEmail) {
            e.preventDefault();
            window.emailVerification.showError('يرجى التحقق من البريد الإلكتروني الحالي');
            window.emailVerification.resetVerification();
            return;
        }

        // Terms validation
        const termsCheckbox = form.querySelector('#terms');
        if (!termsCheckbox.checked) {
            e.preventDefault();
            showTermsError('يجب الموافقة على الشروط والأحكام');
            return;
        }

        console.log('✅ All validations passed, submitting form...');

        // Tüm validasyonlar geçerse loading state göster
        submitBtn.disabled = true;
        submitBtn.classList.add('loading');
        btnText.textContent = 'جاري إنشاء الحساب...';
        btnLoader.style.display = 'flex';

        // Form gönderildiğinde localStorage'ı temizle
        localStorage.removeItem('email_verified');
        localStorage.removeItem('verified_email');
    });
}

function showTermsError(message) {
    const existingError = document.querySelector('.terms-error');
    if (existingError) {
        existingError.remove();
    }

    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message animate-error terms-error';
    errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;

    const termsContainer = document.querySelector('.terms-container');
    termsContainer.parentNode.insertBefore(errorDiv, termsContainer.nextSibling);
}

// Form reset handler
function handleFormReset() {
    const form = document.querySelector('.auth-form');
    if (!form) return;

    // Form resetlendiğinde veya sayfa yenilendiğinde doğrulamayı sıfırla
    form.addEventListener('reset', function () {
        window.emailVerification.resetVerification();
    });
}

// Initialize everything when DOM is loaded
document.addEventListener('DOMContentLoaded', function () {
    // Initialize Email verification
    window.emailVerification = new EmailVerification();

    // Password strength checking
    const passwordInput = document.getElementById('password');
    if (passwordInput) {
        passwordInput.addEventListener('input', function (e) {
            checkPasswordStrength(e.target.value);
        });
    }

    // Form submission
    handleFormSubmission();

    // Form reset
    handleFormReset();

    // Sayfa kapatıldığında veya yenilendiğinde localStorage'ı temizle
    window.addEventListener('beforeunload', function () {
        // Sadece form gönderilmediyse temizle
        if (!window.formSubmitted) {
            localStorage.removeItem('email_verified');
            localStorage.removeItem('verified_email');
        }
    });

    // Animation on scroll
    const animateOnScroll = function () {
        const elements = document.querySelectorAll('.animate-on-scroll');

        elements.forEach(element => {
            const elementTop = element.getBoundingClientRect().top;
            const elementVisible = 150;

            if (elementTop < window.innerHeight - elementVisible) {
                element.classList.add('active');
            }
        });
    };

    window.addEventListener('scroll', animateOnScroll);
    animateOnScroll();
});
