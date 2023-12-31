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
        Schema::create('share_documents', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('to')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('from')->references('id')->on('users')->onDelete('cascade');
            $table->boolean('opened')->default(false);
            $table->foreignId('document_id')->nullable()->references('id')->on('documents')->onDelete('cascade');
            $table->boolean('toRais')->nullable();
            $table->boolean('isReply');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('share_documents');
    }
};
