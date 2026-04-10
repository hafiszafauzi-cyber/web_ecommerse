<?php
// Application Configuration
return [
    'app' => [
        'name' => 'LuxuryShop',
        'url' => 'http://localhost/ecommerse-amazon',
        'env' => 'development', // development, production
        'debug' => true,
        'timezone' => 'Asia/Jakarta',
    ],
    
    'database' => [
        'driver' => 'mysql',
        'host' => 'localhost',
        'database' => 'ecommerce_db',
        'username' => 'root',
        'password' => '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
    ],
    
    'mail' => [
        'driver' => 'smtp',
        'host' => 'smtp.gmail.com',
        'port' => 587,
        'username' => 'your-email@gmail.com',
        'password' => 'your-password',
        'encryption' => 'tls',
        'from' => [
            'address' => 'noreply@yourdomain.com',
            'name' => 'Your Store Name',
        ],
    ],
    
    'payment' => [
        'midtrans' => [
            'server_key' => 'your-server-key',
            'client_key' => 'your-client-key',
            'is_production' => false,
            'sanitize' => true,
        ],
    ],
    
    'cache' => [
        'driver' => 'file', // file, redis, memcached
        'lifetime' => 3600,
    ],
    
    'session' => [
        'lifetime' => 120,
        'expire_on_close' => false,
        'encrypt' => true,
        'same_site' => 'lax',
    ],
];
?>