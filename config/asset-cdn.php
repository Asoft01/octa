<?php

return [

    'use_cdn' => env('USE_CDN', false),

    'cdn_url' => env('CDN_URL', 'https://cdn.agora.community/'),

    'filesystem' => [
        'disk' => 's3',

        'options' => [
            'CacheControl' => 'max-age=31536000, public', // Sets HTTP Header 'cache-control'. The client should cache the file for max 1 year 
        ],
    ],

    'files' => [
        'ignoreDotFiles' => true,

        'ignoreVCS' => true,

        'include' => [
            'paths' => [
                'js',
                'css',
                'img',
                'fonts',
                'photos'
            ],
            'files' => [
                //
            ],
            'extensions' => [
                //
            ],
            'patterns' => [
                //
            ],
        ],

        'exclude' => [
            'paths' => [
                //
            ],
            'files' => [
                //
            ],
            'extensions' => [
                //
            ],
            'patterns' => [
                //
            ],
        ],
    ],

];
