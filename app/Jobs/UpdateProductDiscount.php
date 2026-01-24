<?php

namespace App\Jobs;
use App\Models\product;


use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class UpdateProductDiscount implements ShouldQueue
{
    use Queueable;


    /**
     * Create a new job instance.
     */
    public function __construct()
    {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $products=Product::all();
        foreach($products as $x)
        {
            $x  ->  price   =     5;
            $x->save();
        }

    }
}
