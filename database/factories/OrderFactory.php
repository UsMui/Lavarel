<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "order_date"=>now(),
            "grand_total"=>0,
            "shipping_address"=>$this->faker->address,
            "customer_tel"=>$this->faker->phoneNumber,
            "fullname"=>$this->faker->name,
            "country"=>$this->faker->country,
            "city"=>$this->faker->city,
            "zip"=>$this->faker->postcode,
            "email"=>$this->faker->email
        ];
    }
}
