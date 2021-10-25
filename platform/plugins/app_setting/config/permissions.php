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
    [
        'name'        => 'Edit',
        'flag'        => 'app_setting.edit',
        'parent_flag' => 'app_setting.index',
    ],
    [
        'name' => 'App settings',
        'flag' => 'time.index',
    ],
    [
        'name'        => 'Create',
        'flag'        => 'time.create',
        'parent_flag' => 'time.index',
    ],
    [
        'name'        => 'Edit',
        'flag'        => 'time.edit',
        'parent_flag' => 'time.index',
    ],
    [
        'name'        => 'Delete',
        'flag'        => 'time.destroy',
        'parent_flag' => 'time.index',
    ],
];
