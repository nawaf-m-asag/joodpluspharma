<?php

Route::group(['namespace' => 'Botble\Notification\Http\Controllers', 'middleware' => ['web', 'core']], function () {
    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'notification', 'as' => 'notification.'], function () {
   
            Route::resource('', 'NotificationController')->parameters(['' => 'notification']);
                Route::get('/', [
                    'as'         => 'index',
                    'uses'       => 'NotificationController@index',
                    'permission' => 'notification.edit',
                ]);
                Route::delete('items/destroy', [
                    'as'         => 'deletes',
                    'uses'       => 'NotificationController@deletes',
                    'permission' => 'notification.edit',
                ]);
                
                Route::get('edit/{id}', [
                    'as'         => 'edit',
                    'uses'       => 'NotificationController@edit',
                    'permission' => 'notification.edit',
                ]);
                
                Route::delete('items/destroy/{id}', [
                    'as'         => 'destroy',
                    'uses'       => 'NotificationController@destroy',
                    'permission' => 'notification.destroy',
                ]);
                Route::get('/one-notification', [
                    'as'         => 'one-notification',
                    'uses'       => 'NotificationController@OneNotification',
                    'permission' => 'notification.edit',
                ]);
                Route::post('/one-notification', [
                    'as'         => 'one-notification',
                    'uses'       => 'NotificationController@SendOneNotification',
                    'permission' => 'notification.edit',
                ]);
              
            
        });
    });
});

