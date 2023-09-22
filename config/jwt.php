<?php

return [

    /*
    |--------------------------------------------------------------------------
    | JWT Secret Key
    |--------------------------------------------------------------------------
    |
    | This key will be used to sign your tokens. You should set it to a
    | random, 32-character string. Keep this key secret.
    |
    */

    'secret' => env('JWT_SECRET', 'qQigorZRt3MGMMdhgbD6qj8W6f5w1ZIE5oOxAyDSwI1iFMsSCmNljTVTW4NwWuo'),

    /*
    |--------------------------------------------------------------------------
    | JWT TTL (Time to Live)
    |--------------------------------------------------------------------------
    |
    | This value represents the time (in minutes) that the token will be
    | valid after being issued.
    |
    */

    'ttl' => env('JWT_TTL', 60),

    /*
    |--------------------------------------------------------------------------
    | JWT Refresh TTL
    |--------------------------------------------------------------------------
    |
    | This value represents the maximum time (in minutes) that a token
    | can be refreshed after its initial issuance.
    |
    */

    'refresh_ttl' => env('JWT_REFRESH_TTL', 20160), // 2 weeks

    /*
    |--------------------------------------------------------------------------
    | JWT hashing algorithm
    |--------------------------------------------------------------------------
    |
    | Specify the hashing algorithm that will be used to sign the token.
    |
    | See here: https://github.com/namshi/jose/tree/master/src/Namshi/JOSE/Signer/OpenSSL
    | for possible values.
    |
    */

    'algo' => env('JWT_ALGO', 'HS256'),

];
