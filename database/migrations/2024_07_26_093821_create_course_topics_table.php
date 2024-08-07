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
        Schema::disableForeignKeyConstraints();

        Schema::create('course_topics', function (Blueprint $table) {
            $table->id();
            $table->string('course_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamp('datetime');
            $table->enum('status', ["upcoming","ongoing","completed","cancelled"])->default('upcoming');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_topics');
    }
};
