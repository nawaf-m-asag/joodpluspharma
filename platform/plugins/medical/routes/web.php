<?php

Route::group(['namespace' => 'Botble\Medical\Http\Controllers', 'middleware' => ['web', 'core']], function () {
    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'services', 'as' => 'service.'], function () {
   
            Route::resource('', 'ServiceController')->parameters(['' => 'service']);
                Route::get('/', [
                    'as'         => 'index',
                    'uses'       => 'ServiceController@index',
                    'permission' => 'services.index',
                ]);

                Route::delete('items/destroy', [
                    'as'         => 'deletes',
                    'uses'       => 'ServiceController@deletes',
                    'permission' => 'services.edit',
                ]);
                
                Route::get('edit/{id}', [
                    'as'         => 'edit',
                    'uses'       => 'ServiceController@edit',
                    'permission' => 'services.edit',
                ]);
                
                Route::delete('items/destroy/{id}', [
                    'as'         => 'destroy',
                    'uses'       => 'ServiceController@destroy',
                    'permission' => 'services.destroy',
                ]);
        });

        //////////////////////////////
        Route::group(['prefix' => 'prescriptions', 'as' => 'prescription.'], function () {
   
        Route::resource('', 'PrescriptionController')->parameters(['' => 'prescription']);
        Route::get('/', [
            'as'         => 'index',
            'uses'       => 'PrescriptionController@index',
            'permission' => 'prescriptions.index',
        ]);

        Route::delete('items/destroy', [
            'as'         => 'deletes',
            'uses'       => 'ServiceController@deletes',
            'permission' => 'services.edit',
        ]);
        
        Route::get('edit/{id}', [
            'as'         => 'edit',
            'uses'       => 'ServiceController@edit',
            'permission' => 'services.edit',
        ]);
        
        Route::delete('items/destroy/{id}', [
            'as'         => 'destroy',
            'uses'       => 'ServiceController@destroy',
            'permission' => 'services.destroy',
        ]);
   
    });
});

});

