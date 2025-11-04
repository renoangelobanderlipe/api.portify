<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tech_stacks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('name');
            $table->string('placeholder')->nullable();
            $table->string('icon_tag')->unique();
            $table->integer('icon_size')->nullable()->default(54);
            $table->string('provider')->nullable();
            $table->string('category');
            $table->timestamps();

            $table->index(['user_id', 'name', 'icon_tag']);

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tech_stacks');
    }
};
