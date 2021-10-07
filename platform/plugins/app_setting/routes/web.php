<?php

Route::group(['namespace' => 'Botble\App_setting\Http\Controllers', 'middleware' => ['web', 'core']], function () {

    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {

        Route::group(['prefix' => 'cities', 'as' => 'city.'], function () {
            Route::resource('', 'CityController')->parameters(['' => 'city']);
            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'CityController@deletes',
                'permission' => 'city.destroy',
            ]);
        });


        Route::group(['prefix' => 'areas', 'as' => 'area.'], function () {
            Route::resource('', 'AreaController')->parameters(['' => 'area']);
            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'AreaController@deletes',
                'permission' => 'area.destroy',
            ]);
        });
    });

});
