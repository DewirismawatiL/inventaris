<?php

return [
    'defaults' => [
        'guard' => 'api', //nama middlewer/ mengindefikasi nama middlewer
        'passwords' => 'users',
    ],

    'guards' => [
        'api' => [
            'driver' => 'jwt',
            'provider' => 'users', // provides mencari letak model username
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => \App\Models\User::class
        ]
    ]
];