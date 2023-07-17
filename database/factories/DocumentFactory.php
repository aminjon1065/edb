<?php

namespace Database\Factories;

use App\Models\Document;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class DocumentFactory extends Factory
{
    protected $model = Document::class;

    public function definition()
    {
        return [
            'uuid' => $this->faker->uuid(),
            'title' => $this->faker->word(),
            'content' => $this->faker->word(),
            'control' => $this->faker->boolean(),
            'status' => $this->faker->word(),
            'type' => $this->faker->word(),
            'user_id' => $this->faker->randomNumber(),
            'date_done' => Carbon::now(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'deleted_at' => Carbon::now(),
        ];
    }
}
