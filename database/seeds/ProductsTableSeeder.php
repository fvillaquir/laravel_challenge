<?php
use App\Product;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Product::insert([
            [
                'nombre' => 'Remera',
                'descripcion' => 'Descripcion de remera',
                'precio' => 19.99,
                'stock' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Buzo',
                'descripcion' => 'Descripcion de buzo',
                'precio' => 29.99,
                'stock' => 200,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Campera',
                'descripcion' => 'Descripcion de campera',
                'precio' => 39.99,
                'stock' => 150,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
