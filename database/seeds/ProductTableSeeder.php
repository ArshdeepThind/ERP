<?php

use Illuminate\Database\Seeder;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product = new \App\Product([
          'imagePath' => 'http://ecx.images-amazon.com/images/I/51ZU%2BCvkTyL.jpg',
          'title' => 'Harry Potter',
          'description' => 'Super cool',
          'price' => 10,
        ]);
        $product->save();

        $product = new \App\Product([
          'imagePath' => 'http://ecx.images-amazon.com/images/I/51ZU%2BCvkTyL.jpg',
          'title' => 'The subtle art of not giving a fuck',
          'description' => 'Super cool',
          'price' => 45,
        ]);
        $product->save();

        $product = new \App\Product([
          'imagePath' => 'http://ecx.images-amazon.com/images/I/51ZU%2BCvkTyL.jpg',
          'title' => 'Half Girl friend',
          'description' => 'Super cool',
          'price' => 15,
        ]);
        $product->save();

        $product = new \App\Product([
          'imagePath' => 'http://ecx.images-amazon.com/images/I/51ZU%2BCvkTyL.jpg',
          'title' => 'Life is what you make it',
          'description' => 'Super cool',
          'price' => 30,
        ]);
        $product->save();

        $product = new \App\Product([
          'imagePath' => 'http://ecx.images-amazon.com/images/I/51ZU%2BCvkTyL.jpg',
          'title' => 'Accidental prime minister',
          'description' => 'Super cool',
          'price' => 20,
        ]);
        $product->save();
    }
}
