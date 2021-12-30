<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'geo'], function () {

    Route::get('search/{name}/{parent_id?}', '\Mdhesari\LaravelCities\Http\Controllers\GeoController@search');

    Route::get('item/{id}', '\Mdhesari\LaravelCities\Http\Controllers\GeoController@item');

    Route::get('children/{id}', '\Mdhesari\LaravelCities\Http\Controllers\GeoController@children');

    Route::get('parent/{id}', '\Mdhesari\LaravelCities\Http\Controllers\GeoController@parent');

    Route::get('country/{code}', '\Mdhesari\LaravelCities\Http\Controllers\GeoController@country');

    Route::get('countries', '\Mdhesari\LaravelCities\Http\Controllers\GeoController@countries');

    Route::get('ancestors/{id}', '\Mdhesari\LaravelCities\Http\Controllers\GeoController@ancestors');

    Route::get('breadcrumbs/{id}', '\Mdhesari\LaravelCities\Http\Controllers\GeoController@breadcrumbs');

});