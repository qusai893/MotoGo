<?php
// app/Http/Controllers/Auth/EmailVerificationController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\EmailVerificationCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerificationMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


class EmailVerificationController extends Controller
{
    /**
     * Email doğrulama kodu gönder (Register için)
     */
    public function sendVerificationCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        // Email'in başka kullanıcı tarafından kullanılıp kullanılmadığını kontrol et
        $existingUser = User::where('email', $request->email)->first();
        if ($existingUser) {
            return response()->json([
                'success' => false,
                'message' => 'هذا البريد الإلكتروني مسجل مسبقاً'
            ], 422);
        }

        // Eski doğrulama kodlarını temizle
        EmailVerificationCode::where('email', $request->email)->delete();

        // Yeni doğrulama kodu oluştur
        $code = $this->generateVerificationCode();

        $verification = EmailVerificationCode::create([
            'email' => $request->email,
            'code' => $code,
            'expires_at' => Carbon::now()->addMinutes(30),
            'verified' => false
        ]);

        // Email gönder
        try {
            Mail::to($request->email)->send(new EmailVerificationMail($code));

            return response()->json([
                'success' => true,
                'message' => 'تم إرسال رمز التحقق إلى بريدك الإلكتروني'
            ]);
        } catch (\Exception $e) {
            Log::error('Email sending failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'فشل في إرسال رمز التحقق. يرجى المحاولة مرة أخرى.'
            ], 500);
        }
    }

    /**
     * Email doğrulama işlemi (Register için)
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6'
        ]);

        // Kodu kontrol et
        $verification = EmailVerificationCode::where('email', $request->email)
            ->where('code', $request->code)
            ->where('expires_at', '>', Carbon::now())
            ->where('verified', false)
            ->first();

        if (!$verification) {
            return response()->json([
                'success' => false,
                'message' => 'رمز التحقق غير صحيح أو منتهي الصلاحية'
            ], 422);
        }

        // Kodu doğrulanmış olarak işaretle
        $verification->update(['verified' => true]);

        $user = Auth::user();
        //  $user->email_verified_at = now();
        return response()->json([
            'success' => true,
            'message' => 'تم التحقق بنجاح! يمكنك الآن إنشاء الحساب'
        ]);
    }

    /**
     * 6 haneli doğrulama kodu oluştur
     */
    private function generateVerificationCode()
    {
        return str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }
}
