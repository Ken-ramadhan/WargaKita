<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * @var array<int, string>|string|null
     */
    protected $proxies = null; // Bisa juga array ['**'] untuk pengembangan

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    
        
    
    protected function proxies()
    {
        // Untuk pengembangan dengan Ngrok, Anda bisa mengembalikan ini:
        return ['**']; // Ini akan mempercayai semua proxy. HATI-HATI DI LINGKUNGAN PRODUKSI!
        // Atau jika Anda ingin lebih spesifik dari .env:
        // return explode(',', env('TRUSTED_PROXIES', ''));
    }
}