{{-- resources/views/emails/contact.blade.php --}}
<!DOCTYPE html>
<html dir="rtl">

<head>
    <meta charset="utf-8">
    <title>طلب جديد من نموذج الاتصال</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .content {
            padding: 30px;
        }

        .field {
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            border-right: 4px solid #e74c3c;
        }

        .field-label {
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .field-value {
            color: #555;
            font-size: 16px;
        }

        .message-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-right: 4px solid #3498db;
            white-space: pre-wrap;
            line-height: 1.8;
        }

        .recaptcha-info {
            background: #e8f5e8;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #2ecc71;
            margin-top: 20px;
        }

        .footer {
            background: #ecf0f1;
            padding: 20px;
            text-align: center;
            color: #7f8c8d;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>📩 طلب جديد من نموذج الاتصال</h1>
            <p>تم استلام طلب جديد من خلال موقعك</p>
        </div>

        <div class="content">
            <div class="field">
                <div class="field-label">👤 الاسم الكامل:</div>
                <div class="field-value">{{ $name }}</div>
            </div>

            <div class="field">
                <div class="field-label">📧 البريد الإلكتروني:</div>
                <div class="field-value">{{ $email }}</div>
            </div>

            <div class="field">
                <div class="field-label">📞 رقم الهاتف:</div>
                <div class="field-value">{{ $phone }}</div>
            </div>

            <div class="field">
                <div class="field-label">🎯 نوع الخدمة المطلوبة:</div>
                <div class="field-value">{{ $service_type }}</div>
            </div>

            <div class="field">
                <div class="field-label">💬 تفاصيل الطلب أو الاستفسار:</div>
                <div class="message-box">{{ $message_content }}</div>
            </div>

            @isset($recaptcha_score)
                <div class="recaptcha-info">
                    <div class="field-label">🛡️ معلومات الأمان (reCAPTCHA):</div>
                    <div class="field-value">
                        <strong>مستوى الثقة:</strong> {{ $recaptcha_score }}
                        -
                        ({{ $recaptcha_score >= 0.7 ? 'عالية الثقة' : ($recaptcha_score >= 0.5 ? 'متوسطة الثقة' : 'منخفضة الثقة') }})
                    </div>
                </div>
            @endisset
        </div>

        <div class="footer">
            <p>⏰ تم استلام هذه الرسالة في {{ now()->format('Y-m-d H:i:s') }}</p>
            <p><strong>ملاحظة:</strong> يمكنك الرد على هذا البريد للتواصل مباشرة مع العميل.</p>
        </div>
    </div>
</body>

</html>
