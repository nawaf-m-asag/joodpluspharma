<?php


Route::group([
    'namespace'  => 'Botble\Medical\Http\Controllers\Customers',
    'middleware' => ['web', 'core', 'customer'],
    'prefix'     => 'customer',
    'as'         => 'customer.',
], function () {

    Route::get('prescription/create', [
        'as'   => 'prescription.create',
        'uses' => 'PublicController@getAddPrescription',
    ]);

    Route::post('prescription/create', [
        'as'   => 'prescription.create',
        'uses' => 'PublicController@getSetPrescription',
    ]);

   
});

