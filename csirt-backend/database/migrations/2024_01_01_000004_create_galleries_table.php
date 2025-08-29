<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image_path');
            $table->string('thumbnail_path')->nullable();
            $table->enum('category', ['events', 'training', 'meetings', 'conferences', 'general']);
            $table->foreignId('uploaded_by')->constrained('users');
            $table->boolean('is_featured')->default(false);
            $table->integer('order')->default(0);
            $table->json('metadata')->nullable(); // Image size, dimensions, etc.
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('galleries');
    }
};