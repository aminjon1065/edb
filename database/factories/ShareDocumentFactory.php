<?php

namespace Database\Factories;

use App\Models\ShareDocument;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ShareDocumentFactory extends Factory
{
    protected $model = ShareDocument::class;

    public function definition()
    {
        return [
            'uuid' => $this->faker->uuid(),
            'to' => $this->faker->randomNumber(),
            'from' => $this->faker->randomNumber(),
            'opened' => $this->faker->boolean(),
            'document_id' => $this->faker->randomNumber(),
            'toRais' => $this->faker->boolean(),
            'isReply' => $this->faker->boolean(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'deleted_at' => Carbon::now(),
        ];
    }
}
