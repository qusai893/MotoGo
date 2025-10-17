{{-- resources/views/emails/verification.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>رمز التحقق - Verification Code</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <div style="text-align: center; margin-bottom: 30px;">
            <h1 style="color: #2c3e50;">رمز التحقق</h1>
        </div>

        <div style="background: #f8f9fa; padding: 20px; border-radius: 10px; text-align: center;">
            <h2 style="color: #2c3e50; margin-bottom: 20px;">:رمز التحقق الخاص بك</h2>
            <div style="font-size: 32px; font-weight: bold; letter-spacing: 10px; color: #e74c3c;
                      background: white; padding: 15px; border-radius: 5px; display: inline-block;">
                {{ $code }}
            </div>
            <p style="margin-top: 20px; color: #7f8c8d;">
                هذا الرمز صالح لمدة 10 دقائق فقط
            </p>
        </div>

        <div style="margin-top: 30px; padding: 20px; background: #ecf0f1; border-radius: 5px;">
            <p style="margin: 0; color: #7f8c8d;">
                إذا لم تطلب هذا الرمز، يرجى تجاهل هذه الرسالة.
            </p>
        </div>
    </div>
</body>
</html>
