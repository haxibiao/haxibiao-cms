<?php

declare (strict_types = 1);

use Illuminate\Contracts\Routing\Registrar as RouteRegisterContract;
use Illuminate\Support\Facades\Route;

//调试和日志查看
Route::get('/logshow', 'LogController@logShow');
Route::get('/logclear', 'LogController@logClear');
Route::get('/debug', 'LogController@debug');

/**
 * 站点管理
 */
Route::group(['prefix' => 'site'], function (RouteRegisterContract $route) {
    // //管理专题
    // Route::get('/list', CategoryController::class . '@list');
});

//站点地图索引
Route::get('/sitemap', SitemapController::class . '@index');
//单个地图
Route::get('/sitemap/{name_en}', SitemapController::class . '@name_en');
