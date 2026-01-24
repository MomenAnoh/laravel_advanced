<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\Order;
use App\Models\product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Momen',
            'email' => 'momennoh123aa@gmail.com',
            'password' => '20042004'
            ]);
        product::create([
            'name' => 'Product 1',
            'des'=>'des of product',
            'price'=>5,
            'quantity'=>5
        ]);
        Cart::create([
            'user_id' => 1,
        ]);
        Order::create([
            'cart_id'=>1,
            'user_id'=>1,
        ]);
        DB::table('cart_products')->insert([
            'cart_id'=>1,
            'product_id'=>1,
            'quantity'=>5,
        ]);
    }
}
