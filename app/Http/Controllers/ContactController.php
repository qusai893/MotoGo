<?php
// app/Http/Controllers/ContactController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        // reCAPTCHA doğrulama
        $recaptchaResponse = $this->verifyRecaptcha($request->input('g-recaptcha-response'));

        if (!$recaptchaResponse['success']) {
            Log::warning('reCAPTCHA verification failed', [
                'score' => $recaptchaResponse['score'] ?? 'N/A',
                'errors' => $recaptchaResponse['error-codes'] ?? []
            ]);

            return response()->json([
                'success' => false,
                'message' => 'فشل التحقق من reCAPTCHA. يرجى المحاولة مرة أخرى.'
            ], 400);
        }

        // reCAPTCHA skor kontrolü
        if ($recaptchaResponse['score'] < config('recaptcha.threshold', 0.5)) {
            Log::warning('reCAPTCHA score too low', [
                'score' => $recaptchaResponse['score'],
                'threshold' => config('recaptcha.threshold', 0.5)
            ]);

            return response()->json([
                'success' => false,
                'message' => 'تم اكتشاف نشاط مشبوه. يرجى المحاولة مرة أخرى.'
            ], 400);
        }

        // Validasyon
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'service_type' => 'required|string|max:255',
            'message' => 'required|string|max:1000'
        ], [
            'name.required' => 'الاسم الكامل مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'يرجى إدخال بريد إلكتروني صحيح',
            'phone.required' => 'رقم الهاتف مطلوب',
            'service_type.required' => 'نوع الخدمة مطلوب',
            'message.required' => 'تفاصيل الطلب مطلوبة'
        ]);

        try {
            // Email gönder
            Mail::send('emails.contact', [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'service_type' => $validated['service_type'],
                'message_content' => $validated['message'],
                'recaptcha_score' => $recaptchaResponse['score']
            ], function ($message) use ($validated) {
                $message->to('rabeeclane2@gmail.com')
                    ->subject('طلب جديد من نموذج الاتصال - ' . $validated['name'])
                    ->replyTo($validated['email']);
            });

            Log::info('Contact form submitted successfully', [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'recaptcha_score' => $recaptchaResponse['score']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم إرسال رسالتك بنجاح. سنقوم بالرد عليك في أقرب وقت.'
            ]);
        } catch (\Exception $e) {
            Log::error('Contact form submission failed', [
                'error' => $e->getMessage(),
                'name' => $validated['name'],
                'email' => $validated['email']
            ]);

            return response()->json([
                'success' => false,
                'message' => 'فشل في إرسال الرسالة. يرجى المحاولة مرة أخرى.'
            ], 500);
        }
    }

    /**
     * Google reCAPTCHA doğrulama
     */
    private function verifyRecaptcha($token)
    {
        // Local development için test modu
        if (app()->environment('local')) {
            Log::info('reCAPTCHA test mode active', [
                'token' => $token ? 'provided' : 'missing',
                'host' => request()->getHost()
            ]);

            return [
                'success' => true,
                'score' => 0.9, // Yüksek skor
                'action' => 'contact',
                'hostname' => request()->getHost(),
                'challenge_ts' => now()->toISOString(),
            ];
        }

        if (empty($token)) {
            return ['success' => false, 'error-codes' => ['missing-token']];
        }

        try {
            $response = Http::timeout(10)->asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => config('recaptcha.secret_key'),
                'response' => $token,
                'remoteip' => request()->ip()
            ]);

            $result = $response->json();
            Log::info('reCAPTCHA verification result', $result);

            return $result;
        } catch (\Exception $e) {
            Log::error('reCAPTCHA verification request failed', [
                'error' => $e->getMessage()
            ]);

            return ['success' => false, 'error-codes' => ['request-failed']];
        }
    }
}
