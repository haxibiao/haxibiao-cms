<?php

use Illuminate\Contracts\Routing\Registrar as RouteRegisterContract;
use Illuminate\Support\Facades\Route;

/**
 * 站点管理
 */
Route::group(['prefix' => 'api'], function (RouteRegisterContract $api) {

    $api->group(['prefix' => 'site'], function (RouteRegisterContract $api) {
        //站点api配额查询反馈
        Route::get('/pushResult', 'SitemapController@pushResult');
    });

});
