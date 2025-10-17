<?php
// app/Http/Controllers/Auth/RegisterController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\EmailVerificationCode;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function validator(array $data)
    {
        Log::info('Registration validator called', $data);

        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'terms' => ['required', 'accepted'],
            'verification_code' => ['required', 'string', 'size:6'],
        ], [
            'terms.required' => 'يجب الموافقة على الشروط والأحكام',
            'terms.accepted' => 'يجب الموافقة على الشروط والأحكام',
            'verification_code.required' => 'يرجى إدخال رمز التحقق',
            'verification_code.size' => 'رمز التحقق يجب أن يكون 6 أرقام',
        ]);
    }

    public function register(Request $request)
    {
        Log::info('Registration request started', $request->all());

        // Önce email doğrulamasını kontrol et
        $verificationCode = EmailVerificationCode::where('email', $request->email)
            ->where('verified', true)
            ->where('expires_at', '>', now())
            ->first();

        if (!$verificationCode) {
            Log::error('Email verification failed for registration', [
                'email' => $request->email
            ]);
            return redirect()->back()
                ->withErrors(['verification_code' => 'يرجى التحقق من البريد الإلكتروني أولاً'])
                ->withInput();
        }

        // Validasyonu yap
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            Log::error('Registration validation failed', [
                'errors' => $validator->errors()->toArray()
            ]);
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Kullanıcıyı oluştur
        try {
            $user = $this->create($request->all());

            Log::info('User created successfully', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);

            // Kullanılan kodu temizle
            $verificationCode->delete();

            // Kullanıcıyı login et ve event fırlat
            $this->guard()->login($user);
            event(new Registered($user));

            Log::info('User registered and logged in successfully');

            return redirect($this->redirectPath());
        } catch (\Exception $e) {
            Log::error('User creation failed', [
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'فشل في إنشاء الحساب. يرجى المحاولة مرة أخرى.'])
                ->withInput();
        }
    }

    protected function create(array $data)
    {
        Log::info('Creating user with data', $data);

        try {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'email_verified_at' => now(), // Email zaten doğrulandı
            ]);

            Log::info('User created in database', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);

            return $user;
        } catch (\Exception $e) {
            Log::error('Database error during user creation', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Doğrulama kodu gönder
     */
    public function sendVerificationCode(Request $request)
    {
        Log::info('Send verification code request', $request->all());

        $request->validate([
            'email' => 'required|email'
        ]);

        // 6 haneli kod oluştur
        $code = sprintf("%06d", random_int(1, 999999));

        // Kodu veritabanına kaydet
        $verificationCode = EmailVerificationCode::updateOrCreate(
            ['email' => $request->email],
            [
                'code' => $code,
                'expires_at' => now()->addMinutes(10),
                'verified' => false
            ]
        );

        // Email gönder
        try {
            Mail::send('emails.verification', ['code' => $code], function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('رمز التحقق - Verification Code');
            });

            Log::info('Verification email sent successfully', [
                'email' => $request->email,
                'code' => $code
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم إرسال رمز التحقق إلى بريدك الإلكتروني',
                'code' => $code // Development için
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send verification email', [
                'error' => $e->getMessage(),
                'email' => $request->email
            ]);

            // Hata durumunda kodu sil
            $verificationCode->delete();

            return response()->json([
                'success' => false,
                'message' => 'فشل في إرسال رمز التحقق. يرجى المحاولة مرة أخرى.'
            ], 500);
        }
    }

    /**
     * Kodu doğrula
     */
    public function verifyCode(Request $request)
    {
        Log::info('Verify code request', $request->all());

        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6'
        ]);

        $verificationCode = EmailVerificationCode::where('email', $request->email)
            ->where('code', $request->code)
            ->where('verified', false)
            ->first();

        if (!$verificationCode) {
            return response()->json([
                'success' => false,
                'message' => 'رمز التحقق غير صحيح'
            ], 400);
        }

        if ($verificationCode->expires_at->isPast()) {
            $verificationCode->delete();
            return response()->json([
                'success' => false,
                'message' => 'انتهت صلاحية رمز التحقق'
            ], 400);
        }

        // Kodu doğrula
        $verificationCode->update(['verified' => true]);

        return response()->json([
            'success' => true,
            'message' => 'تم التحقق بنجاح'
        ]);
    }
}
