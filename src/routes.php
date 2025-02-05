<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'geo'], function() {

    Route::get('search/{name}/{parent_id?}', 	'\Igaster\LaravelCities\GeoController@search');

    Route::post('searchCountries', 	'\Igaster\LaravelCities\GeoController@searchCountries')->name('geo.searchCountries');
    Route::post('searchStates', 	'\Igaster\LaravelCities\GeoController@searchStates')->name('geo.searchStates');
    Route::post('searchCities', 	'\Igaster\LaravelCities\GeoController@searchCities')->name('geo.searchCities');

    Route::get('item/{id}', 		'\Igaster\LaravelCities\GeoController@item');
    
    Route::get('items/{id}', 		'\Igaster\LaravelCities\GeoController@items');

    Route::get('children/{id}', 	'\Igaster\LaravelCities\GeoController@children');

    Route::get('parent/{id}', 	'\Igaster\LaravelCities\GeoController@parent');

    Route::get('country/{code}',	'\Igaster\LaravelCities\GeoController@country');

    Route::get('countries', 		'\Igaster\LaravelCities\GeoController@countries');

    Route::get('ancestors/{id}','\Igaster\LaravelCities\GeoController@ancestors');

    Route::get('breadcrumbs/{id}','\Igaster\LaravelCities\GeoController@breadcrumbs');

});

