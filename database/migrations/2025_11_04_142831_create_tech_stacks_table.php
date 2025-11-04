<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tech_stacks');
    }
};
