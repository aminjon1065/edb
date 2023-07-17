<?php

namespace Database\Factories;

use App\Models\File;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class FileFactory extends Factory
{
    protected $model = File::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'size' => $this->faker->word(),
            'extension' => $this->faker->word(),
            'document_id' => $this->faker->randomNumber(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'deleted_at' => Carbon::now(),
        ];
    }
}
