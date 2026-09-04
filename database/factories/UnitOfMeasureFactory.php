<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UnitOfMeasureFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'Unidad',
            'abbreviation' => 'Un',
            'active' => true,
        ];
    }
}
