<?php

namespace Database\Factories\Master;
use App\Models\Master;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
class ManagerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->name,
            'manager_id' => Str::slug($this->faker->name),
            'branch_id' => $this->faker->text,
        ];
    }
}
