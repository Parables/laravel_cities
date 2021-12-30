<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'geo'], function() {

    Route::get('search/{name}/{parent_id?}', 	'\Mdhesari\LaravelCities\GeoController@search');

    Route::get('item/{id}', 		'\Mdhesari\LaravelCities\GeoController@item');

    Route::get('children/{id}', 	'\Mdhesari\LaravelCities\GeoController@children');

    Route::get('parent/{id}', 	'\Mdhesari\LaravelCities\GeoController@parent');

    Route::get('country/{code}',	'\Mdhesari\LaravelCities\GeoController@country');

    Route::get('countries', 		'\Mdhesari\LaravelCities\GeoController@countries');

    Route::get('ancestors/{id}','\Mdhesari\LaravelCities\GeoController@ancestors');

    Route::get('breadcrumbs/{id}','\Mdhesari\LaravelCities\GeoController@breadcrumbs');

});

