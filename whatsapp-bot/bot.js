require('dotenv').config();
const { Client, LocalAuth } = require('whatsapp-web.js');
const qrcode = require('qrcode-terminal');
const express = require('express');
const cors = require('cors');
const fs = require('fs');
const path = require('path');

const app = express();
app.use(cors());
app.use(express.json());

// التحقق من مسار Chrome
function getChromePath() {
    // التحقق أولاً من .env
    if (process.env.CHROME_PATH && fs.existsSync(process.env.CHROME_PATH)) {
        return process.env.CHROME_PATH;
    }

    // المسارات المحتملة لـ Chrome
    const possiblePaths = [
        'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe',
        'C:\\Program Files (x86)\\Google\\Chrome\\Application\\chrome.exe',
        process.env.LOCALAPPDATA + '\\Google\\Chrome\\Application\\chrome.exe',
        'C:\\Users\\' + process.env.USERNAME + '\\AppData\\Local\\Google\\Chrome\\Application\\chrome.exe'
    ];

    for (const chromePath of possiblePaths) {
        if (fs.existsSync(chromePath)) {
            console.log('✅ تم العثور على Chrome: ' + chromePath);
            return chromePath;
        }
    }

    console.log('❌ لم يتم العثور على Chrome. يرجى التثبيت يدويًا.');
    return null;
}

const chromePath = getChromePath();

// نظام الحد من الطلبات
class SimpleRateLimiter {
    constructor() {
        this.ipLimits = new Map();
        this.numberLimits = new Map();
        this.messageCount = 0;
        this.lastReset = Date.now();

        setInterval(() => {
            this.messageCount = 0;
            this.lastReset = Date.now();
            console.log('🔄 تم إعادة تعيين عداد الرسائل كل ساعة');
        }, 3600000);
    }

    checkIpLimit(ip) {
        const now = Date.now();
        const ipData = this.ipLimits.get(ip) || { count: 0, lastRequest: 0 };

        if (now - ipData.lastRequest < 300000 && ipData.count >= 10) return false;
        if (now - ipData.lastRequest >= 300000) ipData.count = 0;

        ipData.count++;
        ipData.lastRequest = now;
        this.ipLimits.set(ip, ipData);
        return true;
    }

    checkNumberLimit(phoneNumber) {
        const now = Date.now();
        const numberData = this.numberLimits.get(phoneNumber) || { count: 0, lastRequest: 0 };

        if (now - numberData.lastRequest < 3600000 && numberData.count >= 3) return false;
        if (now - numberData.lastRequest >= 3600000) numberData.count = 0;

        numberData.count++;
        numberData.lastRequest = now;
        this.numberLimits.set(phoneNumber, numberData);
        return true;
    }

    checkMessageLimit() {
        const maxMessages = parseInt(process.env.MAX_MESSAGES_PER_HOUR) || 30;
        return this.messageCount < maxMessages;
    }

    incrementMessageCount() { this.messageCount++; }

    getStats() {
        const maxMessages = parseInt(process.env.MAX_MESSAGES_PER_HOUR) || 30;
        return {
            messagesSent: this.messageCount,
            maxMessagesPerHour: maxMessages,
            remainingMessages: maxMessages - this.messageCount,
            nextReset: new Date(this.lastReset + 3600000).toISOString()
        };
    }
}

const rateLimiter = new SimpleRateLimiter();

// التحقق من مفتاح API
const authenticateApiKey = (req, res, next) => {
    const apiKey = req.headers['x-api-key'];
    if (!apiKey || apiKey !== process.env.API_KEY) {
        return res.status(401).json({ success: false, error: 'مفتاح API غير صالح' });
    }
    next();
};

// التحقق من رموز الدول المسموحة
function isAllowedCountryCode(phoneNumber) {
    const allowedCodes = process.env.ALLOWED_COUNTRY_CODES ?
        process.env.ALLOWED_COUNTRY_CODES.split(',') : ['963', '90', '971', '966'];
    const cleanNumber = phoneNumber.replace(/\s+/g, '').replace('+', '');
    return allowedCodes.some(code => cleanNumber.startsWith(code));
}

// إعدادات عميل WhatsApp - مع مسار Chrome
const clientConfig = {
    authStrategy: new LocalAuth({ clientId: "verification-bot" }),
    puppeteer: {
        headless: false, // false لعرض واجهة المستخدم
        args: [
            '--no-sandbox',
            '--disable-setuid-sandbox',
            '--disable-dev-shm-usage',
            '--disable-accelerated-2d-canvas',
            '--no-first-run',
            '--no-zygote',
            '--disable-gpu'
        ]
    }
};

// إضافة مسار Chrome إذا تم العثور عليه
if (chromePath) {
    clientConfig.puppeteer.executablePath = chromePath;
    console.log('🚀 جاري البدء باستخدام Chrome...');
} else {
    console.log('⚠️  لم يتم العثور على Chrome، سيتم فتح المتصفح تلقائيًا...');
}

const client = new Client(clientConfig);

// المتغيرات
let isReady = false;
let qrCode = null;

// رمز QR
client.on('qr', (qr) => {
    console.log('📱 يرجى مسح رمز QR:');
    qrcode.generate(qr, { small: true });
    qrCode = qr;
});

// الاتصال جاهز
client.on('ready', () => {
    console.log('✅ اتصال WhatsApp جاهز!');
    console.log('📞 رقم المرسل: ' + (process.env.WHATSAPP_SENDER_NUMBER || '+963994796625'));
    isReady = true;
    qrCode = null;
});

// انقطاع الاتصال
client.on('disconnected', (reason) => {
    console.log('❌ انقطع اتصال WhatsApp:', reason);
    isReady = false;
    setTimeout(() => {
        console.log('🔄 جاري إعادة الاتصال...');
        client.initialize();
    }, 10000);
});

// دالة إرسال رمز التحقق
async function sendVerificationCode(targetPhoneNumber, code) {
    try {
        if (!isReady) throw new Error('اتصال WhatsApp غير جاهز.');

        if (!isAllowedCountryCode(targetPhoneNumber)) {
            const allowedCodes = process.env.ALLOWED_COUNTRY_CODES ?
                process.env.ALLOWED_COUNTRY_CODES.split(',') : ['963', '90', '971', '966'];
            throw new Error(`رمز الدولة غير مدعوم. الرموز المدعومة: ${allowedCodes.join(', ')}`);
        }

        if (!rateLimiter.checkMessageLimit()) {
            throw new Error('تم الوصول إلى الحد الأقصى للرسائل في الساعة.');
        }

        const cleanNumber = targetPhoneNumber.replace(/\s+/g, '').replace('+', '');
        const formattedNumber = `${cleanNumber}@c.us`;

        console.log('🔍 جاري التحقق من الرقم: ' + targetPhoneNumber);

        const isRegistered = await client.isRegisteredUser(formattedNumber);
        if (!isRegistered) throw new Error('رقم الهاتف هذا غير مسجل في WhatsApp');

        const message = `🔐 رمز التحقق الخاص بك: *${code}*\n\nهذا الرمز صالح لمدة 10 دقائق.`;

        console.log('📨 جاري إرسال الرسالة إلى الرقم: ' + targetPhoneNumber);
        await client.sendMessage(formattedNumber, message);

        rateLimiter.incrementMessageCount();
        await new Promise(resolve => setTimeout(resolve, 2000));

        return {
            success: true,
            message: 'تم إرسال رمز التحقق',
            timestamp: new Date().toISOString()
        };

    } catch (error) {
        console.error('❌ خطأ في إرسال الرسالة:', error.message);
        throw error;
    }
}

// نقاط نهاية API
app.get('/status', authenticateApiKey, (req, res) => {
    const stats = rateLimiter.getStats();
    res.json({
        success: true,
        ready: isReady,
        hasQr: !!qrCode,
        senderNumber: process.env.WHATSAPP_SENDER_NUMBER || '+963994796625',
        stats: stats,
        chrome: !!chromePath
    });
});

app.get('/qr', authenticateApiKey, (req, res) => {
    if (!qrCode) {
        return res.json({ success: false, message: isReady ? 'البوت متصل' : 'بانتظار رمز QR' });
    }
    res.json({ success: true, qr: qrCode });
});

app.post('/send-code', authenticateApiKey, async (req, res) => {
    try {
        const clientIp = req.ip || req.connection.remoteAddress;
        if (!rateLimiter.checkIpLimit(clientIp)) {
            return res.status(429).json({ success: false, error: 'طلبات كثيرة جدًا. يرجى الانتظار 5 دقائق.' });
        }

        const { phoneNumber, code } = req.body;
        if (!phoneNumber || !code) {
            return res.status(400).json({ success: false, error: 'رقم الهاتف والرمز مطلوبان' });
        }

        if (code.length !== 6 || !/^\d+$/.test(code)) {
            return res.status(400).json({ success: false, error: 'يجب أن يتكون الرمز من 6 أرقام' });
        }

        if (!isAllowedCountryCode(phoneNumber)) {
            const allowedCodes = process.env.ALLOWED_COUNTRY_CODES ?
                process.env.ALLOWED_COUNTRY_CODES.split(',') : ['963', '90', '971', '966'];
            return res.status(400).json({ success: false, error: `رمز الدولة غير مدعوم: ${allowedCodes.join(', ')}` });
        }

        if (!rateLimiter.checkNumberLimit(phoneNumber)) {
            return res.status(429).json({ success: false, error: 'تم إرسال العديد من الرموز إلى هذا الرقم. يرجى الانتظار ساعة واحدة.' });
        }

        const result = await sendVerificationCode(phoneNumber, code);
        res.json(result);

    } catch (error) {
        res.status(500).json({ success: false, error: error.message });
    }
});

app.get('/', (req, res) => {
    res.json({
        message: 'WhatsApp Verification Bot API',
        version: '1.0.0',
        status: isReady ? 'جاهز' : 'جاري التهيئة',
        chrome: !!chromePath
    });
});

// بدء البوت
console.log('🚀 جاري بدء بوت WhatsApp...');
client.initialize();

const PORT = process.env.PORT || 3000;
app.listen(PORT, '0.0.0.0', () => {
    console.log('✅ بوت WhatsApp API يعمل على المنفذ: ' + PORT);
    console.log('📞 رقم المرسل: ' + (process.env.WHATSAPP_SENDER_NUMBER || '+963994796625'));
    console.log('🔍 حالة Chrome: ' + (chromePath ? 'تم العثور عليه' : 'غير موجود'));
});

process.on('SIGINT', async () => {
    console.log('🛑 جاري إيقاف البوت...');
    await client.destroy();
    process.exit(0);
});
