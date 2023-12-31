<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('to_rais', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->foreignId('management_id')->references('id')->on('users');
            $table->foreignId('document_id')->references('id')->on('documents')->onDelete('cascade');
            $table->json('replyTo')->nullable();
            $table->boolean('opened')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('to_rais');
    }
};
