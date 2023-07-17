<?php

namespace Database\Factories;

use App\Models\ReplyToDocument;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ReplyToDocumentFactory extends Factory
{
    protected $model = ReplyToDocument::class;

    public function definition()
    {
        return [
            'to' => $this->faker->randomNumber(),
            'from' => $this->faker->randomNumber(),
            'document_id' => $this->faker->randomNumber(),
            'reply_document_id' => $this->faker->randomNumber(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'deleted_at' => Carbon::now(),
        ];
    }
}
