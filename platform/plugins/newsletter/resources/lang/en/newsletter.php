<?php

return [
    'name'     => 'المشتركين',
    'settings' => [
        'email' => [
            'templates' => [
                'title'       => 'المشتركين',
                'description' => 'تكوين قوالب البريد الإلكتروني للمشتركين',
                'to_admin'    => [
                    'title'       => 'إرسال بريد إلكتروني إلى المسؤول',
                    'description' => 'نموذج لإرسال بريد إلكتروني إلى المسؤول',
                ],
                'to_user'     => [
                    'title'       => 'إرسال بريد إلكتروني إلى المستخدم',
                    'description' => 'قالب لإرسال رسالة إلى المشتركين',
                ],
            ],
        ],
                'title'             => 'المشتركين',
        'description'       => 'اعدادت المشتركين',
        'mailchimp_api_key' => 'Mailchimp API Key',
        'mailchimp_list_id' => 'Mailchimp List ID',
        'sendgrid_api_key'  => 'Sendgrid API Key',
        'sendgrid_list_id'  => 'Sendgrid List ID',
        ],
    'statuses' => [
        'subscribed'   => 'مشترك',
        'unsubscribed' => 'غير مشترك',
    ],
];
