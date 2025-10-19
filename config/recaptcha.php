<?php
// config/recaptcha.php

return [
    'site_key' => env('RECAPTCHA_SITE_KEY',),
    'secret_key' => env('RECAPTCHA_SECRET_KEY',),
    'threshold' => env('RECAPTCHA_THRESHOLD',),
];
