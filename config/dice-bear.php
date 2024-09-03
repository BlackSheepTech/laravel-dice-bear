<?php

return [

    /*-------------------------------------------------------------------------
    | DiceBear Base URL
    |--------------------------------------------------------------------------
    |
    | This value is the base URL used by the package to generate the requests
    | to the DiceBear API.
    |
    */

    'base_url' => env('DICE_BEAR_BASE_URL', 'https://api.dicebear.com/9.x/'),

    /*-------------------------------------------------------------------------
    | Available Styles
    |--------------------------------------------------------------------------
    |
    | Styles available to be used by the package.
    |
    */

    'available-styles' => [
        'adventurerNeutral',
        'botttsNeutral',
        'initials',
    ],

    /*-------------------------------------------------------------------------
    | Default Input Values
    |--------------------------------------------------------------------------
    |
    | The default values loaded by the package when creating a new DiceBear
    | instance.
    |
    */

    'defaults' => [
        //General options default values
        'general' => [
            'format' => 'svg',
            'seed' => null,
            'flip' => false,
            'rotate' => 0,
            'scale' => 100,
            'radius' => 0,
            'size' => 64,
            'backgroundType' => 'solid',
            'backgroundRotation' => null,
            'translateX' => 0,
            'translateY' => 0,
            'clip' => false,
            'randomizeIds' => false,
        ],
        //Style specific options default values
        'style' => [
            'adventurerNeutral' => [
                'eyebrows' => '',
                'eyes' => '',
                'mouth' => '',
                'glasses' => '',
                'glassesProbability' => 0,
            ],
            'botttsNeutral' => [

            ],
            'initials' => [

            ],
        ],

    ],

    /*-------------------------------------------------------------------------
    | Default API Values
    |--------------------------------------------------------------------------
    |
    | The default values used by the DiceBear API.
    | Those are the default values used by the DiceBear API and are used to
    | determinate if a parameter can be omitted from the request and should be
    | changed only if you know what you are doing.
    |
    */

    'api-defaults' => [
        //General options default values
        'general' => [
            'format' => 'svg',
            'seed' => null,
            'flip' => false,
            'rotate' => 0,
            'scale' => 100,
            'radius' => 0,
            'size' => 64,
            'backgroundType' => 'solid',
            'backgroundRotation' => null,
            'translateX' => 0,
            'translateY' => 0,
            'clip' => false,
            'randomizeIds' => false,
        ],
        //Style specific options default values
        'style' => [
            'adventurerNeutral' => [

            ],
            'botttsNeutral' => [

            ],
            'initials' => [

            ],
        ],

    ],
];
