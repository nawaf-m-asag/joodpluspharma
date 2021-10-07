<?php
return [
    '401_title' => 'طلب الاذن مرفوض',
    '401_msg'   => '<li>لم يتم منحك حق الوصول إلى القسم من قبل المسؤول.</li>
	                <li>قد يكون لديك نوع حساب خاطئ.</li>
	                <li>أنت غير مصرح لك لعرض الموارد المطلوبة.</li>
	                <li>قد يكون اشتراكك قد انتهى.</li>',
    '404_title' => 'لا يمكن العثور على الصفحة',
    '404_msg'   => '<li>الصفحة المطلوبة غير موجودة.</li>
	                <li>الارتباط الذي نقرت عليه لم يعد موجود</li>
	                <li>ربما انتقلت الصفحة إلى مكان جديد.</li>
	                <li>ربما حدث خطأ.</li>
	                <li>أنت غير مصرح لك لعرض الموارد المطلوبة.</li>',
    '500_title' => 'لا يمكن تحميل الصفحة',
    '500_msg'   => '<li>ربما انتقلت الصفحة إلى مكان جديد.</li>
	                <li>الارتباط الذي نقرت عليه لم يعد موجود.</li>
	                <li>ربما انتقلت الصفحة إلى مكان جديد.</li>
	                <li>ربما حدث خطأ.</li>
	                <li>أنت غير مصرح لك لعرض الموارد المطلوبة.</li>',
    'reasons'   => 'قد يكون هذا بسبب عدة أسباب',
    'try_again' => 'يرجى المحاولة مرة أخرى بعد بضع دقائق ، أو بدلاً من ذلك العودة إلى الصفحة الرئيسية بواسطة<a href="' . route('dashboard.index') . '">انقر هنا</a>.',
    'not_found' => 'غير موجود',
];
