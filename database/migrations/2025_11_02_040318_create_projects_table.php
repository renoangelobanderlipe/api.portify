<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');

            $table->string('title')->unique();
            $table->text('description')->nullable();

            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->string('url', 2048)->nullable();
            $table->string('repository_link', 2048)->nullable();

            $table->string('project_type')->nullable();
            $table->jsonb('tags')->nullable();

            $table->string('thumbnail_url')->nullable();
            $table->jsonb('other_image_url')->nullable();

            $table->jsonb('metadata')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();

            $table->index(['user_id', 'title', 'project_type']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
