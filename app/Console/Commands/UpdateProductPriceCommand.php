<?php

namespace App\Console\Commands;

use App\Jobs\UpdateProductDiscount;
use Illuminate\Console\Command;

class UpdateProductPriceCommand extends Command
{

    protected $signature = 'update-product-price';  //php artisan product:update-product-price   دا الكوماند الي لم اكتبة ف التيرمنال لارفيل يعرف اني اققصصد الكلاس دا زي مصلا
    protected $description = 'update price of product in Ramadan'; // دي مجرد وصف للكوماند بتظهر لو عمل route list


    public function handle()
    {
        //هنا بنادي بقا الجوب
        UpdateProductDiscount::dispatch();
          // بتطبع رسالة خضراء في التيرمنال كتنبيه للمطور إن الكوماند خلصت بنجاح

        $this->info('Product price update job dispatched successfully!');

    }
}
