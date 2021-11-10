<?php

return [
    [
        'name' => 'medical',
        'flag' => 'medical.index',
    ],
    [
        'name' => 'services',
        'flag' => 'services.index',
        'parent_flag' => 'medical.index',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'services.create',
        'parent_flag' => 'services.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'services.edit',
        'parent_flag' => 'services.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'services.deletes',
        'parent_flag' => 'services.index',
    ],
    //Prescriptions
    [
        'name' => 'prescriptions',
        'flag' => 'prescriptions.index',
        'parent_flag' => 'medical.index',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'prescriptions.create',
        'parent_flag' => 'prescriptions.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'prescriptions.edit',
        'parent_flag' => 'prescriptions.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'prescriptions.deletes',
        'parent_flag' => 'prescriptions.index',
    ],

    //Specialties
    [
        'name' => 'specialties',
        'flag' => 'specialties.index',
        'parent_flag' => 'medical.index',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'specialties.create',
        'parent_flag' => 'specialties.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'specialties.edit',
        'parent_flag' => 'specialties.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'specialties.deletes',
        'parent_flag' => 'specialties.index',
    ],
];
