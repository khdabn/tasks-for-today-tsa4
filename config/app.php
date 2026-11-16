<?php
return [
    'name'=>env('APP_NAME','Tasks for Today'), 'env'=>env('APP_ENV','production'),
    'debug'=>(bool) env('APP_DEBUG',false), 'url'=>env('APP_URL','http://localhost'),
    'timezone'=>env('APP_TIMEZONE','Asia/Manila'), 'locale'=>'en', 'fallback_locale'=>'en', 'faker_locale'=>'en_PH',
    'key'=>env('APP_KEY'), 'cipher'=>'AES-256-CBC', 'previous_keys'=>[],
    'maintenance'=>['driver'=>'file'],
];
