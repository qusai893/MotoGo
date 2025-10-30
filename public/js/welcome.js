// Scroll animasyonları
$(document).ready(function () {
    // Navbar scroll efekti
    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            $('.navbar').addClass('navbar-scrolled');
        } else {
            $('.navbar').removeClass('navbar-scrolled');
        }
    });

    // Scroll animasyonları
    function checkScroll() {
        $('.animate-on-scroll').each(function () {
            var elementTop = $(this).offset().top;
            var elementBottom = elementTop + $(this).outerHeight();
            var viewportTop = $(window).scrollTop();
            var viewportBottom = viewportTop + $(window).height();

            if (elementBottom > viewportTop && elementTop < viewportBottom) {
                $(this).addClass('animated');
            }
        });
    }

    $(window).on('scroll', checkScroll);
    checkScroll(); // Sayfa yüklendiğinde kontrol et

    // Navbar linklerine tıklandığında smooth scroll
    $('.navbar-nav a, .btn[href^="#"]').on('click', function (e) {
        if (this.hash !== "") {
            e.preventDefault();

            const hash = this.hash;

            $('html, body').animate({
                scrollTop: $(hash).offset().top - 70
            }, 800);
        }
    });
});



class ContactForm {
    constructor() {
        this.form = document.getElementById('contactForm');
        this.submitBtn = document.getElementById('submitBtn');
        this.btnText = document.getElementById('btnText');
        this.btnLoader = document.getElementById('btnLoader');
        this.formMessages = document.getElementById('formMessages');
        this.recaptchaSiteKey = '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI';
        this.isLocal = this.checkLocalEnvironment();

        this.init();
    }

    init() {
        if (this.form) {
            this.form.addEventListener('submit', (e) => this.handleSubmit(e));

            // Local environment info
            if (this.isLocal) {
                this.showMessage('وضع التطوير النشط: reCAPTCHA في وضع الاختبار', 'info', false);
            }
        }
    }

    checkLocalEnvironment() {
        return window.location.hostname === 'localhost' ||
            window.location.hostname === '127.0.0.1' ||
            window.location.hostname.endsWith('.test') ||
            window.location.hostname.endsWith('.local');
    }

    async handleSubmit(e) {
        e.preventDefault();

        // Reset previous states
        this.resetValidation();
        this.setLoading(true);

        try {
            // Get reCAPTCHA token
            const token = await this.getRecaptchaToken();
            document.getElementById('g-recaptcha-response').value = token;

            // Submit form
            await this.submitForm();
        } catch (error) {
            console.error('Form submission error:', error);
            this.showMessage('حدث خطأ أثناء الإرسال. يرجى المحاولة مرة أخرى.', 'error');
        } finally {
            this.setLoading(false);
        }
    }

    async getRecaptchaToken() {
        if (this.isLocal) {
            // Local için test token
            return 'test-token-localhost-' + Date.now();
        }

        return new Promise((resolve, reject) => {
            grecaptcha.ready(() => {
                grecaptcha.execute(this.recaptchaSiteKey, {
                    action: 'contact'
                })
                    .then(resolve)
                    .catch(reject);
            });
        });
    }

    async submitForm() {
        const formData = new FormData(this.form);

        try {
            const response = await fetch('/contact', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (data.success) {
                this.showMessage(data.message, 'success');
                this.form.reset();
                document.getElementById('g-recaptcha-response').value = '';
            } else {
                this.showMessage(data.message, 'error');

                // Show validation errors if any
                if (data.errors) {
                    this.showValidationErrors(data.errors);
                }
            }
        } catch (error) {
            throw new Error('Network error');
        }
    }

    setLoading(loading) {
        this.submitBtn.disabled = loading;

        if (loading) {
            this.btnText.textContent = this.isLocal ? 'جاري الاختبار...' : 'جاري الإرسال...';
            this.btnLoader.style.display = 'inline-block';
        } else {
            this.btnText.textContent = 'إرسال الرسالة';
            this.btnLoader.style.display = 'none';
        }
    }

    resetValidation() {
        const inputs = this.form.querySelectorAll('.is-invalid');
        inputs.forEach(input => input.classList.remove('is-invalid'));

        const errorElements = this.form.querySelectorAll('.invalid-feedback');
        errorElements.forEach(el => el.textContent = '');
    }

    showValidationErrors(errors) {
        Object.keys(errors).forEach(field => {
            const input = this.form.querySelector(`[name="${field}"]`);
            const errorElement = document.getElementById(`${field}Error`);

            if (input && errorElement) {
                input.classList.add('is-invalid');
                errorElement.textContent = errors[field][0];
            }
        });
    }

    showMessage(message, type, autoClose = true) {
        const alertClass = this.getAlertClass(type);
        const icon = this.getIcon(type);

        this.formMessages.innerHTML = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                <i class="fas ${icon} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;

        if (autoClose && type === 'success') {
            setTimeout(() => {
                const alert = this.formMessages.querySelector('.alert');
                if (alert) {
                    alert.remove();
                }
            }, 5000);
        }
    }

    getAlertClass(type) {
        const classes = {
            success: 'alert-success',
            error: 'alert-danger',
            info: 'alert-info',
            warning: 'alert-warning'
        };
        return classes[type] || 'alert-info';
    }

    getIcon(type) {
        const icons = {
            success: 'fa-check-circle',
            error: 'fa-exclamation-circle',
            info: 'fa-info-circle',
            warning: 'fa-exclamation-triangle'
        };
        return icons[type] || 'fa-info-circle';
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function () {
    new ContactForm();
});
