<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->string('incident_id')->unique(); // CSIRT-2024-001
            $table->string('title');
            $table->text('description');
            $table->enum('severity', ['low', 'medium', 'high', 'critical']);
            $table->enum('category', ['malware', 'phishing', 'ddos', 'data_breach', 'unauthorized_access', 'vulnerability', 'other']);
            $table->enum('status', ['open', 'investigating', 'resolved', 'closed']);
            $table->enum('priority', ['low', 'medium', 'high', 'critical']);
            $table->foreignId('assigned_to')->nullable()->constrained('users');
            $table->foreignId('reported_by')->constrained('users');
            $table->timestamp('detected_at');
            $table->timestamp('resolved_at')->nullable();
            $table->text('impact_description')->nullable();
            $table->json('affected_systems')->nullable();
            $table->json('indicators_of_compromise')->nullable();
            $table->text('remediation_steps')->nullable();
            $table->text('lessons_learned')->nullable();
            $table->json('attachments')->nullable();
            $table->timestamps();

            $table->index(['status', 'severity']);
            $table->index(['category', 'detected_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('incidents');
    }
};