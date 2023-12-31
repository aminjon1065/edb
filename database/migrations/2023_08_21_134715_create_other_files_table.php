<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('other_files', function (Blueprint $table) {
            $table->id();
            $table->string("filename");
            $table->string("path");
            $table->foreignId('user_id')->references("id")->on("users")->onDelete("cascade");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('other_files');
    }
};
