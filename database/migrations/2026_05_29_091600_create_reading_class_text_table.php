<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reading_class_text', function (Blueprint $table) {
            $table->foreignId('reading_class_id')->constrained()->cascadeOnDelete();
            $table->foreignId('text_id')->constrained()->cascadeOnDelete();
            $table->unique(['reading_class_id', 'text_id']);
        });

        Schema::table('reading_classes', function (Blueprint $table) {
            $table->dropForeign(['text_id']);
            $table->dropColumn('text_id');
        });
    }

    public function down(): void
    {
        Schema::table('reading_classes', function (Blueprint $table) {
            $table->foreignId('text_id')->nullable()->constrained()->cascadeOnDelete();
        });

        Schema::dropIfExists('reading_class_text');
    }
};
