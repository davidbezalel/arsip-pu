<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


/**
 * @var routes for administrator
 */
Route::group(['guard' => 'admin'], function () {
    Route::get('/', function () {
        return redirect('/admin/login');
    });
    Route::get('/admin', function () {
        return redirect('/admin/login');
    });
    Route::match(['get', 'post'], '/admin/login', 'Admin\MainController@login');
    Route::match(['get', 'post'], '/admin/register', 'Admin\MainController@register');
    Route::post('/admin/logout', 'Admin\MainController@logout');

    /**
     * @var ppk
     */
    Route::match(['get', 'post'], '/admin/ppk', 'Admin\PPKController@index');
    Route::post('/admin/ppk/add', 'Admin\PPKController@add');
    Route::post('/admin/ppk/update', 'Admin\PPKController@update');
    Route::post('/admin/ppk/delete', 'Admin\PPKController@delete');
    Route::post('/admin/ppk/get', 'Admin\PPKController@get');
    Route::post('/admin/ppk/year/get', 'Admin\PPKController@getyear');

    /**
     * @var paket
     */
    Route::match(['get', 'post'], '/admin/paket', 'Admin\PaketController@index');
    Route::post('/admin/paket/add', 'Admin\PaketController@add');
    Route::post('/admin/paket/update', 'Admin\PaketController@update');
    Route::post('/admin/paket/delete', 'Admin\PaketController@delete');
    Route::post('/admin/paket/get', 'Admin\PaketController@get');
    Route::post('/admin/paket/get/ppk/{id}', 'Admin\PaketController@getbyppk');
    Route::post('/admin/paket/get/subpaket/{id}', 'Admin\PaketController@getsubpaket');
    Route::post('/admin/paket/year/get', 'Admin\PaketController@getyear');

    /**
     * @var kontrak
     */
    Route::match(['get', 'post'], '/admin/kontrak', 'Admin\KontrakController@index');
    Route::post('/admin/kontrak/add', 'Admin\KontrakController@add');

    /**
     * @var report
     */
    Route::get('/admin/laporan', 'Admin\ReportController@index');
    Route::post('/admin/laporan/get', 'Admin\ReportController@get');
    Route::post('/admin/laporan/report', 'Admin\ReportController@serahkanberkas');
    Route::post('/admin/laporan/pinjam', 'Admin\ReportController@pinjamberkas');
    Route::post('/admin/laporan/kembalikan', 'Admin\ReportController@kembalikanberkas');

    /**
     * @var report classification
     */
    Route::post('admin/reportclassification/get', 'Admin\ReportClassificationController@get');
});