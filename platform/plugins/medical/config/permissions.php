<?php

return [
    [
        'name' => 'services',
        'flag' => 'services.index',
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
        'flag'        => 'services.destroy',
        'parent_flag' => 'services.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'services.deletes',
        'parent_flag' => 'services.index',
    ],
    
    [
        'name' => 'prescriptions',
        'flag' => 'prescriptions.index',
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
        'flag'        => 'prescriptions.destroy',
        'parent_flag' => 'prescriptions.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'prescriptions.deletes',
        'parent_flag' => 'prescriptions.index',
    ],
];
