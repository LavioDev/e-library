<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reading_classes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->foreignId('text_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('reading_class_user', function (Blueprint $table) {
            $table->foreignId('reading_class_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->unique(['reading_class_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reading_class_user');
        Schema::dropIfExists('reading_classes');
    }
};

