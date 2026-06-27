<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('assignment_submission_answers')) {
            Schema::create('assignment_submission_answers', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('submission_id')->constrained('assignment_submissions')->cascadeOnDelete();
                $table->foreignId('question_id')->constrained('assignment_questions')->cascadeOnDelete();
                $table->string('selected_answer')->nullable();
                $table->longText('text_answer')->nullable();
                $table->decimal('score', 8, 2)->nullable();
                $table->text('comment')->nullable();
                $table->boolean('auto_graded')->default(false);
                $table->timestamps();
            });
        }

        if (!$this->indexExists('assignment_submission_answers', 'asubans_submission_question_uq')) {
            Schema::table('assignment_submission_answers', function (Blueprint $table): void {
                $table->unique(['submission_id', 'question_id'], 'asubans_submission_question_uq');
            });
        }

        if (!$this->indexExists('assignment_submission_answers', 'asubans_question_autograded_idx')) {
            Schema::table('assignment_submission_answers', function (Blueprint $table): void {
                $table->index(['question_id', 'auto_graded'], 'asubans_question_autograded_idx');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('assignment_submission_answers');
    }

    private function indexExists(string $table, string $indexName): bool
    {
        $driver = DB::getDriverName();
        if ($driver === 'sqlite') {
            $result = DB::select("PRAGMA index_list('".$table."')");
            foreach ($result as $row) {
                if (($row->name ?? null) === $indexName) {
                    return true;
                }
            }

            return false;
        }

        $result = DB::select('SHOW INDEX FROM `'.$table.'` WHERE Key_name = ?', [$indexName]);

        return $result !== [];
    }
};
