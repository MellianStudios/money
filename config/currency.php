<?php

return [
    'base_currency' => 'USD',

    'minimum' => 500,

    'api' => [
        'general' => env('CURRENCY_PAIR_API'),
        'order_book' => env('CURRENCY_ORDER_BOOK_API'),
    ],
];
