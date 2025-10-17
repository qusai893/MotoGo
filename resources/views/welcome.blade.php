@extends('layouts.app')

@section('content')
<head>
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
</head>

    <!-- Hero Section -->
    <section class="hero-section" id="home">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1>حلول شحن سريعة وموثوقة</h1>
                    <p>في موتو جو، نقدم أسرع وأكثر خدمات الشحن موثوقية في حماة، سوريا. نضمن وصول طرودك بسلامة وأمان مع
                        فريق محترف وجاهز لخدمتك على مدار الساعة.</p>
                    <a href="#about" class="btn btn-red-primary btn-lg me-3">المزيد عنا</a>
                    <a href="#contact" class="btn btn-outline-light btn-lg">اتصل بنا</a>
                </div>
                <div class="col-lg-6 text-center">
                    <img src="https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                        alt="موتو جو للشحن" class="img-fluid rounded-3 animate-on-scroll" style="animation-delay: 0.6s;">
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-5" id="about">
        <div class="container py-5">
            <h2 class="text-center section-title">من نحن</h2>
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <img src="{{ asset('assets/Moto Go Logo .jpg') }}" alt="عن موتو جو"
                        class="img-fluid rounded-3 animate-on-scroll">
                </div>
                <div class="col-lg-6">
                    <h3 class="mb-4">خدمات الشحن موتو جو</h3>
                    <p class="mb-4">نحن في موتو جو شركة شحن موثوقة تعمل في مدينة حماة السورية. نضع رضا العملاء في
                        المقام الأول، ونوفر التسليم السريع والآمن مع فريق محترف ومدرب.</p>
                    <p class="mb-4">نتخصص في تقديم حلول شحن مبتكرة تناسب احتياجاتك، سواء كانت طرود صغيرة أو كبيرة،
                        مستندات مهمة، أو حتى طلبات الطعام من مطاعمك المفضلة.</p>
                    <div class="row mt-4">
                        <div class="col-md-6 mb-3">
                            <div class="d-flex">
                                <i class="fas fa-check-circle text-red-primary me-3 mt-1"></i>
                                <div>
                                    <h5>تسليم فائق السرعة</h5>
                                    <p class="mb-0">ضمن مدينة حماة في أقل وقت</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex">
                                <i class="fas fa-check-circle text-red-primary me-3 mt-1"></i>
                                <div>
                                    <h5>تغليف آمن ومحكم</h5>
                                    <p class="mb-0">لضمان وصول أغراضك سليمة</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex">
                                <i class="fas fa-check-circle text-red-primary me-3 mt-1"></i>
                                <div>
                                    <h5>دعم عملاء 24/7</h5>
                                    <p class="mb-0">نحن دائماً بجانبك للإجابة على استفساراتك</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex">
                                <i class="fas fa-check-circle text-red-primary me-3 mt-1"></i>
                                <div>
                                    <h5>أسعار تنافسية</h5>
                                    <p class="mb-0">عروضنا تناسب جميع الميزانيات</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5 bg-gray-custom">
        <div class="container py-5">
            <h2 class="text-center section-title text-white">لماذا تختار موتو جو؟</h2>
            <div class="row mt-5">
                <div class="col-md-4 mb-4">
                    <div class="feature-card bg-white text-center p-4 h-100 animate-on-scroll">
                        <div class="feature-icon">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <h4>تسليم فائق السرعة</h4>
                        <p>نضمن تسليم طلباتك في أسرع وقت ممكن داخل مدينة حماة، مع فريق توصيل مدرب ومجهز بأحدث الوسائل.
                        </p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-card bg-white text-center p-4 h-100 animate-on-scroll"
                        style="animation-delay: 0.2s;">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h4>نقل آمن وموثوق</h4>
                        <p>نستخدم تقنيات تغليف متطورة ونتخذ إجراءات خاصة لضمان وصول أغراضك في أفضل حالة دون تلف.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-card bg-white text-center p-4 h-100 animate-on-scroll"
                        style="animation-delay: 0.4s;">
                        <div class="feature-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h4>دعم فني متكامل</h4>
                        <p>فريق خدمة العملاء لدينا جاهز دائمًا لمساعدتك على مدار الساعة، مع متابعة مستمرة لشحنتك.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="py-5" id="services">
        <div class="container py-5">
            <h2 class="text-center section-title">خدماتنا المميزة</h2>
            <div class="row mt-5">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="service-card animate-on-scroll">
                        <div class="service-icon">
                            <i class="fas fa-box"></i>
                        </div>
                        <h4>تسليم الطرود</h4>
                        <p>نقوم بتسليم طرودك الصغيرة والمتوسطة الحجم بسرعة وأمان مع ضمان التوصيل في الوقت المحدد.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="service-card animate-on-scroll" style="animation-delay: 0.2s;">
                        <div class="service-icon">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <h4>طلبات المطاعم</h4>
                        <p>نحضر طلباتك من مطعمك المفضل إلى عتبة دارك بسرعة فائقة مع الحفاظ على جودة الطعام.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="service-card animate-on-scroll" style="animation-delay: 0.4s;">
                        <div class="service-icon">
                            <i class="fas fa-file-contract"></i>
                        </div>
                        <h4>تسليم المستندات</h4>
                        <p>نقوم بتسليم مستنداتك المهمة بأمان وسرية تامة مع ضمان وصولها للجهة المعنية.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="service-card animate-on-scroll" style="animation-delay: 0.6s;">
                        <div class="service-icon">
                            <i class="fas fa-gift"></i>
                        </div>
                        <h4>تسليم الهدايا</h4>
                        <p>نساعدك في إرسال هدايا مفاجئة لأحبائك في المناسبات الخاصة مع خدمة تغليف مميزة.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="service-card animate-on-scroll" style="animation-delay: 0.8s;">
                        <div class="service-icon">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                        <h4>تسليم التسوق</h4>
                        <p>نحضر مشترياتك من السوق والمتاجر إلى باب منزلك مع الحفاظ على جودة المنتجات.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="service-card animate-on-scroll" style="animation-delay: 1s;">
                        <div class="service-icon">
                            <i class="fas fa-truck-loading"></i>
                        </div>
                        <h4>شحن تجاري</h4>
                        <p>نقدم حلول الشحن التجارية المتكاملة للشركات والمتاجر مع أسعار خاصة للكميات.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Restaurant Order Section -->
    <section class="py-5 bg-red-primary" id="restaurant-order">
        <div class="container py-5 text-center text-white">
            <h2 class="mb-4">اطلب من مطعمك المفضل</h2>
            <p class="mb-5 fs-5">اطلب من مطاعم حماة المفضلة لديك، ودع موتو جو يصل بها إلى عتبة دارك في أسرع وقت!</p>
            <a href="{{ route('restaurants.index') }}" class="btn btn-light btn-lg px-5 py-3">
                <i class="fas fa-utensils me-2"></i>اطلب الآن
            </a>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="py-5 bg-light" id="contact">
        <div class="container py-5">
            <h2 class="text-center section-title">اتصل بنا</h2>
            <div class="row mt-5">
                <div class="col-lg-8 mx-auto">
                    <div class="contact-form animate-on-scroll">
                        <h4 class="mb-4 text-center">نحن هنا لمساعدتك</h4>
                        <p class="text-center mb-4">للاستفسارات أو طلبات الخدمة، يرجى تعبئة النموذج التالي وسنقوم بالرد
                            عليك في أقرب وقت</p>
                        <form id="contactForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" placeholder="الاسم الكامل" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="email" class="form-control" placeholder="البريد الإلكتروني" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="tel" class="form-control" placeholder="رقم الهاتف" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" placeholder="نوع الخدمة المطلوبة"
                                        required>
                                </div>
                            </div>
                            <textarea class="form-control" rows="5" placeholder="تفاصيل الطلب أو الاستفسار" required></textarea>
                            <button type="submit" class="btn btn-red-primary btn-lg w-100 mt-3">إرسال
                                الرسالة</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Scroll animasyonları
        $(document).ready(function() {
            // Navbar scroll efekti
            $(window).scroll(function() {
                if ($(this).scrollTop() > 100) {
                    $('.navbar').addClass('navbar-scrolled');
                } else {
                    $('.navbar').removeClass('navbar-scrolled');
                }
            });

            // Scroll animasyonları
            function checkScroll() {
                $('.animate-on-scroll').each(function() {
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
            $('.navbar-nav a, .btn[href^="#"]').on('click', function(e) {
                if (this.hash !== "") {
                    e.preventDefault();

                    const hash = this.hash;

                    $('html, body').animate({
                        scrollTop: $(hash).offset().top - 70
                    }, 800);
                }
            });
        });
    </script>
@endsection
