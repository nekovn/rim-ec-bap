<?php

use Illuminate\Database\Seeder;
use App\Models\Testproduct;

class TestproductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 25; $i++) {

            $product = new Testproduct();
            $product->name = $i . '番目の商品名';
            $product->unit_price = array_rand([500, 1000, 1500]); // ランダム
            
            $product->save();
        }
    }
}
