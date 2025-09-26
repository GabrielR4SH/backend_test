<?php

return [
    'salt' => env('HASHIDS_SALT', 'payt-backend-test-salt-2025'), // Salt único
    'min_length' => 7, // Garante comprimento mínimo de 7
    'alphabet' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890',

    /*
    |-------------------------------------------------------------------------
    | Default Connection Name
    |-------------------------------------------------------------------------
    |
    */
    'default' => 'main',

    /*
    |-------------------------------------------------------------------------
    | Hashids Connections
    |-------------------------------------------------------------------------
    |
    */
    'connections' => [
        'main' => [
            'salt' => env('HASHIDS_SALT', 'payt-backend-test-salt-2025'), // Usa o mesmo salt
            'length' => 7, // Define o comprimento mínimo aqui
            'alphabet' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890',
        ],

        'alternative' => [
            'salt' => 'your-salt-string',
            'length' => 7,
            'alphabet' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890',
        ],
    ],
];
