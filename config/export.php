<?php
// config/export.php

return [
    'disk' => 'export',   // public/export এ save হবে

    'crawl' => false,     // explicit paths ব্যবহার করব
    'paths' => [
        '/',               // Home page
        '/products',       // Products listing page
        // Dynamic products পরে AppServiceProvider থেকে add করা যাবে
    ],

    'include_files' => [
        // CSS
        'public/assets/user/home.css'    => 'assets/user/home.css',
        'public/assets/user/product.css' => 'assets/user/product.css',

        // JS
        'public/assets/user/loginModal1.js' => 'assets/user/loginModal1.js',
        'public/assets/user/pwaAppV1.js'   => 'assets/user/pwaAppV1.js',
    ],
];
