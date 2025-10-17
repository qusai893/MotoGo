<?php
// app/Services/WhatsAppWebService.php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class WhatsAppWebService
{
    protected $botUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->botUrl = config('services.whatsapp_web.bot_url');
        $this->apiKey = config('services.whatsapp_web.api_key');
    }

    /**
     * إرسال رمز التحقق
     */
    public function sendVerificationCode($phoneNumber, $code)
    {
        try {
            // Rate limiting: رمز واحد لكل رقم خلال 5 دقائق
            $rateLimitKey = 'whatsapp_rate_limit:' . $phoneNumber;
            if (Cache::has($rateLimitKey)) {
                return [
                    'success' => false,
                    'error' => 'لقد أرسلت طلبات كثيرة جدًا. يرجى الانتظار 5 دقائق.'
                ];
            }

            $response = Http::timeout(30)
                ->withHeaders([
                    'X-API-Key' => $this->apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post($this->botUrl . '/send-code', [
                    'phoneNumber' => $phoneNumber,
                    'code' => $code
                ]);

            $result = $response->json();

            if ($response->successful() && $result['success']) {
                // تطبيق rate limit عند الإرسال الناجح
                Cache::put($rateLimitKey, true, 60); // 5 دقائق

                Log::info('تم إرسال رمز التحقق عبر الواتساب', [
                    'phone' => $phoneNumber,
                    'timestamp' => now()
                ]);

                return [
                    'success' => true,
                    'message' => 'تم إرسال رمز التحقق عبر الواتساب'
                ];
            }

            $error = $result['error'] ?? 'خطأ غير معروف';

            Log::error('خطأ في إرسال الواتساب', [
                'phone' => $phoneNumber,
                'error' => $error
            ]);

            return [
                'success' => false,
                'error' => $error
            ];
        } catch (\Exception $e) {
            Log::error('خطأ في خدمة الواتساب', [
                'phone' => $phoneNumber,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => 'تعذر الاتصال بخدمة الواتساب'
            ];
        }
    }

    /**
     * التحقق من حالة البوت
     */
    public function getStatus()
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders(['X-API-Key' => $this->apiKey])
                ->get($this->botUrl . '/status');

            return $response->json() ?? ['success' => false, 'error' => 'خطأ في الاتصال'];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'تعذر الاتصال بخدمة البوت'
            ];
        }
    }

    /**
     * الحصول على رمز QR
     */
    public function getQrCode()
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders(['X-API-Key' => $this->apiKey])
                ->get($this->botUrl . '/qr');

            return $response->json();
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'تعذر الحصول على رمز QR'
            ];
        }
    }
}
