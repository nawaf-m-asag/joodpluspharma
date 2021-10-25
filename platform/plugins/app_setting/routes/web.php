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
        Route::group(['prefix' => 'times', 'as' => 'time.'], function () {
            Route::resource('', 'TimeController')->parameters(['' => 'time']);
            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'TimeController@deletes',
                'permission' => 'time.destroy',
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
        Route::group(['prefix' => 'app-settings', 'as' => 'app_setting.'], function () {
            Route::resource('', 'App_settingsController')->parameters(['' => 'app-settings']);
            Route::get('settings', [
                'as'   => 'get_settings',
                'uses' => 'App_settingController@getSettings',
            ]);

            Route::post('settings', [
                'as'         => 'app_setting.settings.post',
                'permission' => 'app_setting.edit',
                'uses'       => 'App_settingController@postSettings',
            ]);
        });
    });

});
