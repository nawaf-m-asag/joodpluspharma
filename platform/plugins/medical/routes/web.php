<?php

Route::group(['namespace' => 'Botble\Medical\Http\Controllers', 'middleware' => ['web', 'core']], function () {
    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'services', 'as' => 'service.'], function () {
   
            Route::resource('', 'ServiceController')->parameters(['' => 'services']);
                Route::delete('items/destroy', [
                    'as'         => 'deletes',
                    'uses'       => 'ServiceController@deletes',
                    'permission' => 'service.destroy',
                ]);
              
        });

        //////////////////////////////
        Route::group(['prefix' => 'prescriptions', 'as' => 'prescription.'], function () {
        Route::resource('', 'PrescriptionController')->parameters(['' => 'prescription']);

        Route::delete('items/destroy', [
            'as'         => 'deletes',
            'uses'       => 'ServiceController@deletes',
            'permission' => 'services.destroy',
        ]);
   
    });

    Route::group(['prefix' => 'specialties', 'as' => 'specialties.'], function () {
   
        Route::resource('', 'SpecialtiesController')->parameters(['' => 'specialties']);
        Route::delete('items/destroy', [
            'as'         => 'deletes',
            'uses'       => 'SpecialtiesController@deletes',
            'permission' => 'specialties.destroy',
        ]);
           
    });

    Route::group(['prefix' => 'doctors', 'as' => 'doctors.'], function () {
   
        Route::resource('', 'DoctorController')->parameters(['' => 'doctors']);
        Route::delete('items/destroy', [
            'as'         => 'deletes',
            'uses'       => 'DoctorController@deletes',
            'permission' => 'doctors.destroy',
        ]);
           
    });
});

});

