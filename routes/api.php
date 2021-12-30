<?php

use Illuminate\Support\Facades\Route;
use Mdhesari\LaravelCities\Http\Controllers\GeoController;

Route::group(['prefix' => 'geo'], function () {

    Route::get('search/{name}/{parent_id?}', [GeoController::class, 'search');

    Route::get('item/{id}', [GeoController::class, 'item');

    Route::get('children/{id}', [GeoController::class, 'children');

    Route::get('parent/{id}', [GeoController::class, 'parent');

    Route::get('country/{code}', [GeoController::class, 'country');

    Route::get('countries', [GeoController::class, 'countries');

    Route::get('ancestors/{id}', [GeoController::class, 'ancestors');

    Route::get('breadcrumbs/{id}', [GeoController::class, 'breadcrumbs');

});