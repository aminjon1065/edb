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
        Schema::create('reply_to_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('to')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('from')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('document_id')->nullable()->references('id')->on('documents')->onDelete('cascade');
            $table->foreignId('reply_document_id')->nullable()->references('id')->on('documents')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reply_to_documents');
    }
};
