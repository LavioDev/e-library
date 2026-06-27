<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('assignment_questions')) {
            return;
        }

        Schema::create('assignment_questions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('assignment_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['multiple_choice', 'text_input', 'file_input']);
            $table->text('prompt');
            $table->json('options_json')->nullable();
            $table->string('correct_answer')->nullable();
            $table->decimal('max_score', 8, 2)->default(0);
            $table->unsignedInteger('position');
            $table->timestamps();

            $table->unique(['assignment_id', 'position']);
            $table->index(['assignment_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assignment_questions');
    }
};
