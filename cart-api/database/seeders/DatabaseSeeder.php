<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call('UsersTableSeeder');

        DB::table('carts')->insert([
            'ecommerce_id' => 2,
            'customer_id' => 4,
            'status' => 'checkout',
            'date_checkout' => '2022-02-27',
            'price' => 328658,
            'created_at' => '2022-02-27 21:13:48',
            'updated_at' => '2022-02-27 21:44:46'
        ]);

        DB::table('carts_items')->insert([
            'cart_id' => 1,
            'product_sku' => 'PRD1',
            'product_name' => 'Product 1',
            'file_type' => 'pdf',
            'quantity' => 1,
            'price' => 149,
            'delivery_date' => '2022-03-01'
        ]);

        DB::table('carts_items')->insert([
            'cart_id' => 1,
            'product_sku' => 'PRD2',
            'product_name' => 'Product 2',
            'file_type' => 'ai',
            'quantity' => 500,
            'price' => 61875,
            'delivery_date' => '2022-03-03'
        ]);

        DB::table('carts_items')->insert([
            'cart_id' => 1,
            'product_sku' => 'PRD2',
            'product_name' => 'Product 2',
            'file_type' => 'psd',
            'quantity' => 1000,
            'price' => 137700,
            'delivery_date' => '2022-03-02'
        ]);

        DB::table('carts')->insert([
            'ecommerce_id' => 1,
            'customer_id' => 2,
            'status' => 'created',
            'date_checkout' => null,
            'price' => 0,
            'created_at' => '2022-02-28 14:05:58',
            'updated_at' => '2022-02-28 14:05:58'
        ]);
    }
}
