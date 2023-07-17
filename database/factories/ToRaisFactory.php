<?php

namespace Database\Factories;

use App\Models\ToRais;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ToRaisFactory extends Factory
{
    protected $model = ToRais::class;

    public function definition()
    {
        return [
            'document_id' => $this->faker->randomNumber(),
            'replyTo' => $this->faker->words(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'deleted_at' => Carbon::now(),
        ];
    }
}
