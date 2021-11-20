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

    Route::group(['prefix' => 'nursing', 'as' => 'nursing.'], function () {
   
        Route::resource('', 'NursingController')->parameters(['' => 'nursing']);
        Route::delete('items/destroy', [
            'as'         => 'deletes',
            'uses'       => 'NursingController@deletes',
            'permission' => 'nursing.destroy',
        ]);
        Route::get('items/details/{id}', [
            'as'         => 'details',
            'uses'       => 'NursingController@details',
            'permission' => 'nursing.index',
        ]);
           
    });

    Route::group(['prefix' => 'maintenance', 'as' => 'maintenance.'], function () {
   
        Route::resource('', 'MaintenanceController')->parameters(['' => 'maintenance']);
        Route::delete('items/destroy', [
            'as'         => 'deletes',
            'uses'       => 'MaintenanceController@deletes',
            'permission' => 'maintenance.destroy',
        ]);
        Route::get('items/details/{id}', [
            'as'         => 'details',
            'uses'       => 'MaintenanceController@details',
            'permission' => 'maintenance.index',
        ]);
           
    });

    Route::group(['prefix' => 'consulting', 'as' => 'consulting.'], function () {
   
        Route::resource('', 'ConsultingController')->parameters(['' => 'consulting']);
        Route::delete('items/destroy', [
            'as'         => 'deletes',
            'uses'       => 'ConsultingController@deletes',
            'permission' => 'consulting.destroy',
        ]);
        Route::get('items/details/{id}', [
            'as'         => 'details',
            'uses'       => 'ConsultingController@details',
            'permission' => 'consulting.index',
        ]);
           
    });

    //Examinations Route
    Route::group(['prefix' => 'examinations', 'as' => 'examinations.'], function () {
   
        Route::resource('', 'ExaminationsController')->parameters(['' => 'examinations']);
        Route::delete('items/destroy', [
            'as'         => 'deletes',
            'uses'       => 'ExaminationsController@deletes',
            'permission' => 'examinations.destroy',
        ]);
        Route::get('items/details/{id}', [
            'as'         => 'details',
            'uses'       => 'ExaminationsController@details',
            'permission' => 'examinations.index',
        ]);
           
    });
    //Laboratories
    Route::group(['prefix' => 'laboratories', 'as' => 'laboratories.'], function () {
   
        Route::resource('', 'LaboratoriesController')->parameters(['' => 'laboratories']);
        Route::delete('items/destroy', [
            'as'         => 'deletes',
            'uses'       => 'LaboratoriesController@deletes',
            'permission' => 'laboratories.destroy',
        ]);
           
    });
});

});

