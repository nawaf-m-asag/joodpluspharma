<?php

return [
    'cache_management' => 'إدارة ذاكرة التخزين المؤقت',
    'cache_commands'   => 'أوامر مسح ذاكرة التخزين المؤقت',
    'commands'         => [
        'clear_cms_cache'        => [
            'title'       => 'مسح ذاكرة التخزين المؤقت للقاعدة البيانات',
            'description' => 'مسح التخزين المؤقت لقاعدة البيانات',
            'success_msg' => 'تم التنظيف',
        ],
        'refresh_compiled_views' => [
            'title'       => 'تحديث الفيو',
            'description' => 'حذف الفيو',
            'success_msg' => 'تم التحديث',
        ],
        'clear_config_cache'     => [
            'title'       => 'مسح ذاكرة التخزين المؤقت للتكوين',
            'description' => 'قد تحتاج إلى تحديث التخزين المؤقت للتكوين عند تغيير شيء ما في بيئة الإنتاج.',
            'success_msg' => 'تم تنظيف ذاكرة التخزين المؤقت للتهيئة',
        ],
        'clear_route_cache'      => [
            'title'       => 'مسح الراوتينق ',
            'description' => 'مسح توجيه ذاكرة التخزين المؤقت.',
            'success_msg' => 'تم تنظيف ذاكرة التخزين المؤقت',
        ],
        'clear_log'              => [
            'title'       => 'مسح السجل',
            'description' => 'مسح ملفات سجل النظام',
            'success_msg' => 'تم تنظيف سجل النظام',
        ],
    ],
];
