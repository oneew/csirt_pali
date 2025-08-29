<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->enum('category', ['security_alert', 'threat_intelligence', 'vulnerability', 'incident_report', 'best_practices', 'general']);
            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->foreignId('author_id')->constrained('users');
            $table->string('featured_image')->nullable();
            $table->json('tags')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->integer('views_count')->default(0);
            $table->json('meta_data')->nullable(); // SEO and additional metadata
            $table->timestamps();

            $table->index(['status', 'published_at']);
            $table->index(['category', 'priority']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('news');
    }
};