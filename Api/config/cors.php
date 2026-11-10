<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Aquí puedes configurar tus ajustes para CORS.
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    // Orígenes exactos permitidos (Para tu PC local y pruebas)
    'allowed_origins' => [
        'http://localhost:8100',
        'http://localhost',
        'http://127.0.0.1:8000',
        'https://gennda.vercel.app',
    ],

    // PATRÓN DE COMODÍN PARA VERCEL (¡Esta es la clave!)
    // Acepta cualquier dominio que empiece con https://gennda- y termine en .vercel.app
    'allowed_origins_patterns' => [],

    'allowed_headers' => [
        'Accept',
        'Authorization',
        'Content-Type',
        'X-Requested-With',
        'Origin',
    ],

    'exposed_headers' => [],

    'max_age' => 3600,

    // true si envías tokens o cookies
    'supports_credentials' => true,

];
