<?php

return [
    [
        'name' => 'App settings',
        'flag' => 'city.index',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'city.create',
        'parent_flag' => 'city.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'city.edit',
        'parent_flag' => 'city.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'city.destroy',
        'parent_flag' => 'city.index',
    ],


    [
        'name'        => 'Create',
        'flag'        => 'area.create',
        'parent_flag' => 'area.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'area.edit',
        'parent_flag' => 'area.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'area.destroy',
        'parent_flag' => 'area.index',
    ],
];
