// Country code selector functionality
class CountryCodeSelector {
    constructor() {
        this.selector = document.getElementById('countryCodeSelector');
        this.selectedCountry = document.getElementById('selectedCountry');
        this.countryDropdown = document.getElementById('countryDropdown');
        this.countryList = document.getElementById('countryList');
        this.countrySearch = document.getElementById('countrySearch');
        this.phoneInput = document.getElementById('phone');
        this.fullPhoneInput = document.getElementById('full_phone'); // YENİ EKLENDİ
        this.phoneFormGroup = document.getElementById('phone-form-group');
        this.countries = [];
        this.selectedCountryData = null;

        this.init();
    }

    async init() {
        await this.loadCountries();
        this.setupEventListeners();
        this.setDefaultCountry();
        this.updateFullPhone(); // İlk yüklemede full_phone'u güncelle
    }

    async loadCountries() {
        try {
            const response = await fetch('/json/country-codes.json');
            this.countries = await response.json();
            this.renderCountries(this.countries);
        } catch (error) {
            console.error('Failed to load countries:', error);
            this.countries = [
                { code: "+963", name: "سوريا", flag: "🇸🇾" },
                { code: "+966", name: "السعودية", flag: "🇸🇦" },
                { code: "+971", name: "الإمارات", flag: "🇦🇪" },
                { code: "+965", name: "الكويت", flag: "🇰🇼" },
                { code: "+974", name: "قطر", flag: "🇶🇦" },
                { code: "+973", name: "البحرين", flag: "🇧🇭" },
                { code: "+968", name: "عمان", flag: "🇴🇲" },
                { code: "+20", name: "مصر", flag: "🇪🇬" },
                { code: "+962", name: "الأردن", flag: "🇯🇴" },
                { code: "+961", name: "لبنان", flag: "🇱🇧" },
                { code: "+964", name: "العراق", flag: "🇮🇶" },
                { code: "+212", name: "المغرب", flag: "🇲🇦" },
                { code: "+216", name: "تونس", flag: "🇹🇳" },
                { code: "+213", name: "الجزائر", flag: "🇩🇿" },
                { code: "+218", name: "ليبيا", flag: "🇱🇾" },
                { code: "+249", name: "السودان", flag: "🇸🇩" },
                { code: "+967", name: "اليمن", flag: "🇾🇪" },
                { code: "+1", name: "الولايات المتحدة", flag: "🇺🇸" },
                { code: "+44", name: "المملكة المتحدة", flag: "🇬🇧" },
                { code: "+33", name: "فرنسا", flag: "🇫🇷" },
                { code: "+49", name: "ألمانيا", flag: "🇩🇪" },
                { code: "+90", name: "تركيا", flag: "🇹🇷" },
                { code: "+98", name: "إيران", flag: "🇮🇷" }
            ];
            this.renderCountries(this.countries);
        }
    }

    renderCountries(countries) {
        this.countryList.innerHTML = countries.map(country => `
            <div class="country-item" data-code="${country.code}" data-name="${country.name}" data-flag="${country.flag}">
                <span class="country-item-flag">${country.flag}</span>
                <span class="country-item-name">${country.name}</span>
                <span class="country-item-code">${country.code}</span>
            </div>
        `).join('');
    }

    setupEventListeners() {
        this.selectedCountry.addEventListener('click', (e) => {
            e.stopPropagation();
            this.toggleDropdown();
        });

        this.countryList.addEventListener('click', (e) => {
            const countryItem = e.target.closest('.country-item');
            if (countryItem) {
                this.selectCountry(
                    countryItem.dataset.code,
                    countryItem.dataset.name,
                    countryItem.dataset.flag
                );
                this.closeDropdown();
            }
        });

        this.countrySearch.addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            const filteredCountries = this.countries.filter(country =>
                country.name.toLowerCase().includes(searchTerm) ||
                country.code.includes(searchTerm)
            );
            this.renderCountries(filteredCountries);
        });

        document.addEventListener('click', (e) => {
            if (!this.selector.contains(e.target)) {
                this.closeDropdown();
            }
        });

        this.phoneInput.addEventListener('input', (e) => {
            this.validatePhoneInput(e.target.value);
            this.updateFullPhone(); // YENİ: Telefon değişince full_phone'u güncelle
            window.whatsAppVerification.resetVerification();
        });

        this.phoneInput.addEventListener('paste', (e) => {
            const pastedData = e.clipboardData.getData('text');
            if (pastedData.startsWith('+') || pastedData.startsWith('0')) {
                e.preventDefault();
                this.showError('لا يمكنك لصق أرقام تبدأ بـ + أو 0');
            }
        });

        this.countryDropdown.addEventListener('click', (e) => {
            e.stopPropagation();
        });
    }

    toggleDropdown() {
        this.selector.classList.toggle('active');
        if (this.selector.classList.contains('active')) {
            this.countrySearch.focus();
            this.phoneFormGroup.classList.add('active');
        } else {
            this.phoneFormGroup.classList.remove('active');
        }
    }

    closeDropdown() {
        this.selector.classList.remove('active');
        this.countrySearch.value = '';
        this.renderCountries(this.countries);
        this.phoneFormGroup.classList.remove('active');
    }

    selectCountry(code, name, flag) {
        this.selectedCountryData = { code, name, flag };

        this.selectedCountry.innerHTML = `
            <span class="country-flag">${flag}</span>
            <span class="country-code">${code}</span>
            <i class="fas fa-chevron-down"></i>
        `;

        this.updateFullPhone(); // YENİ: Ülke kodu değişince full_phone'u güncelle
    }

    setDefaultCountry() {
        const syria = this.countries.find(country => country.code === '+963');
        if (syria) {
            this.selectCountry(syria.code, syria.name, syria.flag);
        }
    }

    // YENİ: full_phone input'unu güncelle
    updateFullPhone() {
        if (this.fullPhoneInput) {
            const fullPhone = this.getFullPhoneNumber();
            this.fullPhoneInput.value = fullPhone;
            console.log('📞 Full phone updated:', fullPhone);
        }
    }

    validatePhoneInput(value) {
        if (value.startsWith('+') || value.startsWith('0')) {
            this.showError('رقم الهاتف لا يمكن أن يبدأ بـ + أو 0');
            this.phoneInput.value = value.replace(/^[\+0]+/, '');
            return false;
        }

        const numericValue = value.replace(/\D/g, '');
        if (numericValue !== value) {
            this.showError('يجب أن يحتوي رقم الهاتف على أرقام فقط');
            this.phoneInput.value = numericValue;
            return false;
        }

        if (numericValue.length < 8) {
            this.showError('رقم الهاتف يجب أن يحتوي على الأقل 8 أرقام');
            return false;
        }

        if (numericValue.length > 15) {
            this.showError('رقم الهاتف لا يمكن أن يتجاوز 15 رقماً');
            this.phoneInput.value = numericValue.slice(0, 15);
            return false;
        }

        this.hideError();
        return true;
    }

    showError(message) {
        this.hideError();

        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message animate-error';
        errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;

        this.phoneInput.parentNode.appendChild(errorDiv);
        this.phoneInput.classList.add('error');

        setTimeout(() => {
            if (errorDiv.parentNode) {
                errorDiv.remove();
            }
        }, 5000);
    }

    hideError() {
        const existingError = this.phoneInput.parentNode.querySelector('.error-message');
        if (existingError) {
            existingError.remove();
        }
        this.phoneInput.classList.remove('error');
    }

    getFullPhoneNumber() {
        if (!this.selectedCountryData) return '';
        const localNumber = this.phoneInput.value.replace(/\D/g, '');
        return this.selectedCountryData.code + localNumber;
    }

    isValidPhoneNumber() {
        const localNumber = this.phoneInput.value.replace(/\D/g, '');
        return localNumber.length >= 8 && localNumber.length <= 15 && !isNaN(localNumber);
    }
}

// WhatsApp Verification Class
class WhatsAppVerification {
    constructor() {
        this.sendCodeBtn = document.getElementById('sendCodeBtn');
        this.verifyCodeBtn = document.getElementById('verifyCodeBtn');
        this.verificationCodeInput = document.getElementById('verification_code');
        this.verificationSection = document.getElementById('verification-section');
        this.verificationStatus = document.getElementById('verificationStatus');
        this.submitBtn = document.getElementById('submitBtn');
        this.isVerified = false;
        this.verificationCode = '';

        this.init();
    }

    init() {
        this.sendCodeBtn.addEventListener('click', () => this.sendVerificationCode());
        this.verifyCodeBtn.addEventListener('click', () => this.verifyCode());

        // Telefon numarası değiştiğinde doğrulama bölümünü göster/gizle
        document.getElementById('phone').addEventListener('input', () => {
            this.toggleVerificationSection();
        });

        // Form gönderilmeden önce doğrulamayı kontrol et
        const form = document.querySelector('.auth-form');
        form.addEventListener('submit', (e) => {
            if (!this.isVerified) {
                e.preventDefault();
                this.showError('يرجى التحقق من رقم الهاتف أولاً');
                return;
            }

            // Full phone kontrolü - YENİ
            const fullPhone = document.getElementById('full_phone').value;
            if (!fullPhone) {
                e.preventDefault();
                this.showError('رقم الهاتف غير مكتمل');
                return;
            }

            console.log('✅ Form submitting with full_phone:', fullPhone);
        });
    }

    toggleVerificationSection() {
        const phoneValid = window.countryCodeSelector.isValidPhoneNumber();
        if (phoneValid) {
            this.verificationSection.style.display = 'block';
            this.resetVerification();
        } else {
            this.verificationSection.style.display = 'none';
            this.resetVerification();
        }
    }

    resetVerification() {
        this.isVerified = false;
        this.verificationCode = '';
        this.verificationCodeInput.value = '';
        this.submitBtn.disabled = true;
        this.verificationStatus.innerHTML = '';
        this.verifyCodeBtn.innerHTML = '<i class="fas fa-check"></i> تحقق من الرمز';
        this.verifyCodeBtn.classList.remove('btn-success');
        this.verifyCodeBtn.classList.add('btn-outline-success');
    }

    async sendVerificationCode() {
        // Full phone'u güncelle - YENİ
        window.countryCodeSelector.updateFullPhone();

        const fullPhoneNumber = window.countryCodeSelector.getFullPhoneNumber();

        if (!window.countryCodeSelector.isValidPhoneNumber()) {
            window.countryCodeSelector.showError('يرجى إدخال رقم هاتف صحيح');
            return;
        }

        console.log('📤 Sending code to:', fullPhoneNumber);

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
                    full_phone: fullPhoneNumber
                })
            });

            const result = await response.json();

            if (result.success) {
                this.showSuccess('تم إرسال رمز التحقق إلى واتساب الخاص بك');
                this.verificationCodeInput.focus();
                this.verificationCode = result.code;
            } else {
                this.showError(result.message);
            }
        } catch (error) {
            this.showError('حدث خطأ أثناء الإرسال');
        } finally {
            this.sendCodeBtn.disabled = false;
            this.sendCodeBtn.innerHTML = '<i class="fas fa-paper-plane"></i> إرسال الرمز عبر واتساب';
        }
    }

    async verifyCode() {
        // Full phone'u güncelle - YENİ
        window.countryCodeSelector.updateFullPhone();

        const code = this.verificationCodeInput.value.trim();
        const fullPhoneNumber = window.countryCodeSelector.getFullPhoneNumber();

        if (code.length !== 6) {
            this.showError('يرجى إدخال رمز التحقق المكون من 6 أرقام');
            return;
        }

        console.log('🔍 Verifying code for:', fullPhoneNumber);

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
                    full_phone: fullPhoneNumber,
                    code: code
                })
            });

            const result = await response.json();

            if (result.success) {
                this.isVerified = true;
                this.showSuccess('تم التحقق بنجاح! يمكنك الآن إنشاء الحساب');
                this.verifyCodeBtn.innerHTML = '<i class="fas fa-check-circle"></i> تم التحقق';
                this.verifyCodeBtn.classList.remove('btn-outline-success');
                this.verifyCodeBtn.classList.add('btn-success');
                this.submitBtn.disabled = false;

                // Full phone'u son kez güncelle - YENİ
                window.countryCodeSelector.updateFullPhone();
                console.log('✅ Verification successful, full_phone ready:', document.getElementById('full_phone').value);
            } else {
                this.showError(result.message);
            }
        } catch (error) {
            this.showError('حدث خطأ أثناء التحقق');
        } finally {
            this.verifyCodeBtn.disabled = false;
            if (!this.isVerified) {
                this.verifyCodeBtn.innerHTML = '<i class="fas fa-check"></i> تحقق من الرمز';
            }
        }
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

// Form submission handler - GÜNCELLENMİŞ
function handleFormSubmission() {
    const form = document.querySelector('.auth-form');
    if (!form) return;

    const submitBtn = form.querySelector('.auth-btn');
    const btnText = submitBtn.querySelector('.btn-text');
    const btnLoader = submitBtn.querySelector('.btn-loader');

    form.addEventListener('submit', function (e) {
        // Full phone'u son kez güncelle - YENİ
        window.countryCodeSelector.updateFullPhone();

        const fullPhone = document.getElementById('full_phone').value;
        console.log('🚀 Form submission - full_phone:', fullPhone);

        // Önce tüm validasyonları kontrol et
        if (!window.whatsAppVerification.isVerified) {
            e.preventDefault();
            window.whatsAppVerification.showError('يرجى التحقق من رقم الهاتف أولاً');
            return;
        }

        const phoneValid = window.countryCodeSelector.isValidPhoneNumber();
        if (!phoneValid) {
            e.preventDefault();
            window.countryCodeSelector.showError('يرجى إدخال رقم هاتف صحيح');
            return;
        }

        // Full phone kontrolü - YENİ
        if (!fullPhone) {
            e.preventDefault();
            window.whatsAppVerification.showError('رقم الهاتف غير مكتمل');
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
    });
}

function showFormError(message) {
    // Mevcut hataları temizle
    const existingErrors = document.querySelectorAll('.form-global-error');
    existingErrors.forEach(error => error.remove());

    // Yeni hata mesajı oluştur
    const errorDiv = document.createElement('div');
    errorDiv.className = 'alert alert-danger form-global-error mt-3';
    errorDiv.innerHTML = `<i class="fas fa-exclamation-triangle"></i> ${message}`;

    // Formun başına ekle
    const form = document.querySelector('.auth-form');
    form.insertBefore(errorDiv, form.firstChild);

    // Butonu tekrar aktif et
    const submitBtn = document.getElementById('submitBtn');
    if (submitBtn) {
        submitBtn.disabled = false;
        submitBtn.classList.remove('loading');
        submitBtn.querySelector('.btn-text').textContent = 'إنشاء الحساب';
        submitBtn.querySelector('.btn-loader').style.display = 'none';
    }
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

// Initialize everything when DOM is loaded
document.addEventListener('DOMContentLoaded', function () {
    // Initialize country code selector
    window.countryCodeSelector = new CountryCodeSelector();

    // Initialize WhatsApp verification
    window.whatsAppVerification = new WhatsAppVerification();

    // Password strength checking
    const passwordInput = document.getElementById('password');
    if (passwordInput) {
        passwordInput.addEventListener('input', function (e) {
            checkPasswordStrength(e.target.value);
        });
    }

    // Form submission
    handleFormSubmission();

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
