<?php
// app/Http/Controllers/Auth/RegisterController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VerificationCode;
use App\Services\WhatsAppWebService;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/home';
    protected $whatsAppService;

    public function __construct(WhatsAppWebService $whatsAppService)
    {
        $this->middleware('guest');
        $this->whatsAppService = $whatsAppService;
    }

    /**
     * Get a validator for an incoming registration request.
     */
    protected function validator(array $data)
    {
        Log::info('Registration validator called', [
            'data_keys' => array_keys($data),
            'full_phone_received' => $data['full_phone'] ?? 'MISSING',
            'phone_received' => $data['phone'] ?? 'MISSING'
        ]);

        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'regex:/^[1-9][0-9]*$/', 'min:8', 'max:15'],
            'full_phone' => ['required', 'string'], // BU ARTIK FORMDA OLACAK
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'terms' => ['required', 'accepted'],
            'verification_code' => ['required', 'string', 'size:6'],
        ], [
            'phone.regex' => 'رقم الهاتف لا يمكن أن يبدأ بـ 0 أو +',
            'phone.min' => 'رقم الهاتف يجب أن يحتوي على الأقل 8 أرقام',
            'phone.max' => 'رقم الهاتف لا يمكن أن يتجاوز 15 رقماً',
            'full_phone.required' => 'رقم الهاتف الكامل مطلوب', // YENİ HATA MESAJI
            'full_phone.unique' => 'رقم الهاتف مسجل مسبقاً',
            'terms.required' => 'يجب الموافقة على الشروط والأحكام',
            'terms.accepted' => 'يجب الموافقة على الشروط والأحكام',
            'verification_code.required' => 'يرجى إدخال رمز التحقق',
            'verification_code.size' => 'رمز التحقق يجب أن يكون 6 أرقام',
        ]);
    }

    /**
     * Handle a registration request for the application.
     */
    public function register(Request $request)
    {
        Log::info('Registration request started', [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'full_phone' => $request->full_phone, // BU ARTIK GELECEK
            'all_data' => $request->all()
        ]);

        // Önce validasyonu yapalım
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            Log::error('Registration validation failed', [
                'errors' => $validator->errors()->toArray(),
                'input_data' => $request->all()
            ]);
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // WhatsApp doğrulamasını kontrol et
        $verificationCode = VerificationCode::where('phone_number', $request->full_phone)
            ->where('verified', true)
            ->where('expires_at', '>', now())
            ->first();

        if (!$verificationCode) {
            Log::error('Phone verification failed for registration', [
                'phone' => $request->full_phone,
                'available_codes' => VerificationCode::where('phone_number', $request->full_phone)->get()->toArray()
            ]);
            return redirect()->back()
                ->withErrors(['verification_code' => 'يرجى التحقق من رقم الهاتف أولاً'])
                ->withInput();
        }

        // Kullanıcıyı oluştur
        try {
            $user = $this->create($request->all());

            Log::info('User created successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'phone' => $user->phone
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
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input_data' => $request->all()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'فشل في إنشاء الحساب. يرجى المحاولة مرة أخرى.'])
                ->withInput();
        }
    }
    /**
     * Create a new user instance after a valid registration.
     */
    protected function create(array $data)
    {
        Log::info('Creating user with data', [
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['full_phone'], // full_phone'u kullanıyoruz
            'raw_data' => $data
        ]);

        try {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['full_phone'], // full_phone'u kaydediyoruz
                'password' => Hash::make($data['password']),
                'phone_verified_at' => now(),
            ]);

            Log::info('User created in database', [
                'user_id' => $user->id,
                'saved_phone' => $user->phone
            ]);

            return $user;
        } catch (\Exception $e) {
            Log::error('Database error during user creation', [
                'error' => $e->getMessage(),
                'data' => [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'phone' => $data['full_phone']
                ]
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
            'full_phone' => 'required|string'
        ]);

        // 6 haneli kod oluştur
        $code = sprintf("%06d", random_int(1, 999999));

        // Kodu veritabanına kaydet
        $verificationCode = VerificationCode::updateOrCreate(
            ['phone_number' => $request->full_phone],
            [
                'code' => $code,
                'expires_at' => now()->addMinutes(10),
                'verified' => false
            ]
        );

        // WhatsApp'tan gönder
        $result = $this->whatsAppService->sendVerificationCode($request->full_phone, $code);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'message' => 'تم إرسال رمز التحقق إلى واتساب الخاص بك',
                'code' => $code // Development için
            ]);
        }

        // Hata durumunda kodu sil
        $verificationCode->delete();

        return response()->json([
            'success' => false,
            'message' => $result['error']
        ], 500);
    }

    /**
     * Kodu doğrula
     */
    public function verifyCode(Request $request)
    {
        Log::info('Verify code request', $request->all());

        $request->validate([
            'full_phone' => 'required|string',
            'code' => 'required|string|size:6'
        ]);

        $verificationCode = VerificationCode::where('phone_number', $request->full_phone)
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
