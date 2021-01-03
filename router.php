<?php

use Illuminate\Support\Facades\Route;

// APIs routes.
Route::group(
    [
        'middleware' => ['api'],
        'namespace'  => 'Haxibiao\Cms\Http\Api',
    ],
    __DIR__ . '/routes/api.php'
);

// Web routes.
Route::group(
    [
        'middleware' => ['web'],
        'namespace'  => 'Haxibiao\Cms\Http\Controllers',
    ],
    __DIR__ . '/routes/web.php'
);
