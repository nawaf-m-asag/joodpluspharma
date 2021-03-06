<?php

return [
    'name'                                => 'طرق الشحن',
    'shipping'                            => 'الشحن',
    'title'                               => 'العنوان',
    'amount'                              => 'المبلغ',
    'enable'                              => 'تمكين',
    'enabled'                             => 'ممكن',
    'disable'                             => 'تعطيل',
    'disabled'                            => 'معطل',
    'create_shipping'                     => 'إنشاء الشحن',
    'edit_shipping'                       => 'تحرير الشحن :code',
    'status'                              => 'الحالة ',
    'shipping_methods'                    => 'طرق الشحن',
    'create_shipping_method'              => 'إنشاء طريقة الشحن',
    'edit_shipping_method'                => 'تحرير طريقة الشحن',
    'add_shipping_region'                 => 'أضف منطقة الشحن',
    'country'                             => 'البلد',
    'state'                               => 'المنطقة',
    'city'                                => 'المدينة',
    'address'                             => 'العنوان',
    'phone'                               => 'الهاتف',
    'email'                               => 'البريد الالكتروني',
    'zip_code'                            => 'لرمز البريدي',
    'methods'                             => [
        'default' => 'افتراضي',
    ],
    'statuses'                            => [
        'not_approved'  => 'غير مقبول',
        'approved'      => 'مقبول',
        'picking'       => 'Picking',
        'delay_picking' => 'Delay picking',
        'picked'        => 'Picked',
        'not_picked'    => 'Not picked',
        'delivering'    => 'قيد التوصيل',
        'delivered'     => 'تم التوصيل',
        'not_delivered' => 'لم يتم تسليمها',
        'audited'       => 'Audited',
        'canceled'      => 'ألغيت',
    ],
    'cod_statuses'                        => [
        'pending'   => 'قيد الانتظار',
        'completed' => 'مكتمل',
    ],
    'delete'                              => 'حذف',
    'shipping_rules'                      => 'طرق الشحن',
    'shipping_rules_description'          => 'قواعد لحساب رسوم الشحن.',
    'select_country'                      => 'حدد الدولة',
    'add_shipping_rule'                   => 'أضف  طريقة شحن',
    'delete_shipping_rate'                => 'حذف سعر الشحن للمنطقة',
    'delete_shipping_rate_confirmation'   => 'Are you sure you want to delete <strong class="region-price-item-label"></strong> from this shipping area?',
    'delete_shipping_area'                => 'حذف منطقة الشحن',
    'delete_shipping_area_confirmation'   => 'Are you sure you want to delete shipping area <strong class="region-item-label"></strong>?',
    'add_shipping_fee_for_area'           => 'أضف رسوم الشحن للمنطقة',
    'confirm'                             => 'تأكيد',
    'save'                                => 'حفظ',
    'greater_than'                        => 'أكثر من',
    'type'                                => 'النوع',
    'shipping_rule_name'                  => 'اسم طريقة الشحن',
    'shipping_fee'                        => 'رسوم الشحن',
    'cancel'                              => 'إلغاء',
    'base_on_weight'                      => 'بناء على وزن المنتج (جرام)',
    'base_on_price'                       => 'بناء على سعر المنتج',
    'shipment_canceled'                   => 'تم إلغاء الشحنة',
    'at'                                  => 'في',
    'cash_on_delivery'                    => 'الدفع عند الاستلام (COD)',
    'update_shipping_status'              => 'تحديث حالة الشحن',
    'update_cod_status'                   => 'تحديث حالة COD',
    'history'                             => 'التاريخ',
    'shipment_information'                => 'معلومات الشحنة',
    'order_number'                        => 'رقم الطلب',
    'shipping_method'                     => 'طريقة الشحن',
    'select_shipping_method'              => 'إختر طريقة الشحن',
    'cod_status'                          => 'حالة COD',
    'shipping_status'                     => 'حالة الشحن',
    'customer_information'                => 'معلومات العميل',
    'sku'                                 => 'SKU',
    'change_status_confirm_title'         => 'Confirm <span class="shipment-status-label"></span> ?',
    'change_status_confirm_description'   => 'Are you sure you want to confirm <span class="shipment-status-label"></span> for this shipment?',
    'accept'                              => 'قبول',
    'weight_unit'                         => 'الوزن (:unit)',
    'warehouse'                           => 'المخزن',
    'cod_amount'                          => 'الدفع عند الاستلام (COD)',
    'cancel_shipping'                     => 'إلغاء الشحن',
    'shipping_address'                    => 'عنوان الشحن',
    'packages'                            => 'الحزم',
    'edit'                                => 'تعديل',
    'fee'                                 => 'الرسوم',
    'note'                                => 'ملاحظة',
    'finish'                              => 'إنهاء',
    'shipping_fee_cod'                    => 'رسوم الشحن / COD',
    'send_confirmation_email_to_customer' => 'إرسال بريد إلكتروني للتأكيد إلى العميل',
    'form_name'                           => 'الاسم',
    'changed_shipping_status'             => 'Changed status of shipping to : :status . Updated by: %user_name%',
    'order_confirmed_by'                  => 'تم تأكيد الطلب بواسطة %user_name%',
    'shipping_canceled_by'                => 'تم إلغاء الشحن من قبل %user_name%',
    'update_shipping_status_success'      => 'تم تحديث حالة الشحن بنجاح!',
    'update_cod_status_success'           => 'تم تحديث حالة COD للشحن بنجاح!',
    'updated_cod_status_by'               => 'Updated COD status to :status . Updated by: %user_name%',
    'all'                                 => 'الكل',
    'error_when_adding_new_region'        => 'هناك خطأ عند إضافة منطقة جديدة!',
    'delivery'                            => 'توصيل',
    'adjustment_price_of'                 => 'سعر التعديل :key',
     'warehouse'                           => 'المخزن',
];
