<?php

namespace Database\Factories;

use App\Models\InvoiceItem;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceItemFactory extends Factory
{
    protected $model = InvoiceItem::class;

    public function definition(): array
    {
        return [
            'invoice_id' => Invoice::factory(),
            'product_id' => Product::factory(),
            'warehouse_id' => Warehouse::factory(),
            'description' => $this->faker->sentence(),
            'quantity' => $this->faker->randomFloat(2, 1, 100),
            'unit_price' => $this->faker->randomFloat(2, 1, 1000),
            'discount' => 0,
            'tax' => 0,
            'subtotal' => $this->faker->randomFloat(2, 1, 1000),
            'total' => $this->faker->randomFloat(2, 1, 1000),
        ];
    }
}
