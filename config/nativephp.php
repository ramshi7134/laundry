<?php

return [
    /**
     * The version of your app.
     * It is used to determine if the app needs to be updated.
     * Increment this value every time you release a new version.
     */
    'version' => env('NATIVEPHP_APP_VERSION', '1.0.0'),

    /**
     * The ID of your application. This should be unique.
     */
    'app_id' => env('NATIVEPHP_APP_ID', 'com.laundrypos.app'),

    /**
     * If your application allows deep linking, you can specify the scheme
     * to handle here.
     */
    'deeplink_scheme' => env('NATIVEPHP_DEEPLINK_SCHEME', 'nativephp'),

    /**
     * The author of your application.
     */
    'author' => env('NATIVEPHP_APP_AUTHOR', 'Laundry POS Team'),

    /**
     * The default window configuration.
     */
    'window' => [
        'width' => 1200,
        'height' => 800,
        'min_width' => 1024,
        'min_height' => 768,
        'title' => 'Laundry POS System',
        'route' => '/pos', // Boot directly into POS initially
        'show' => true,
    ],

    /**
     * The roles of your application.
     */
    'roles' => [
        'admin' => 'Admin Role',
    ],

    /**
     * The default menu configuration.
     */
    'menu' => [
        //
    ],
];
