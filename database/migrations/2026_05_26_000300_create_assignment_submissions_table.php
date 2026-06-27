<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('assignment_submissions')) {
            Schema::create('assignment_submissions', function (Blueprint $table): void {
                $table->id();
                $table->foreignId('assignment_id')->constrained()->cascadeOnDelete();
                $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
                $table->unsignedInteger('attempt_no');
                $table->enum('status', ['draft', 'submitted', 'graded'])->default('draft');
                $table->timestamp('submitted_at')->nullable();
                $table->timestamp('graded_at')->nullable();
                $table->foreignId('graded_by')->nullable()->constrained('users')->cascadeOnDelete();
                $table->decimal('total_score', 10, 2)->nullable();
                $table->text('overall_comment')->nullable();
                $table->timestamps();
            });
        }

        $this->addUniqueIfMissing(
            'assignment_submissions',
            'asub_assign_student_attempt_uq',
            ['assignment_id', 'student_id', 'attempt_no']
        );
        $this->addIndexIfMissing(
            'assignment_submissions',
            'asub_assign_student_status_idx',
            ['assignment_id', 'student_id', 'status']
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('assignment_submissions');
    }

    /**
     * @param array<int, string> $columns
     */
    private function addUniqueIfMissing(string $table, string $indexName, array $columns): void
    {
        if ($this->indexExists($table, $indexName)) {
            return;
        }

        Schema::table($table, function (Blueprint $blueprint) use ($columns, $indexName): void {
            $blueprint->unique($columns, $indexName);
        });
    }

    /**
     * @param array<int, string> $columns
     */
    private function addIndexIfMissing(string $table, string $indexName, array $columns): void
    {
        if ($this->indexExists($table, $indexName)) {
            return;
        }

        Schema::table($table, function (Blueprint $blueprint) use ($columns, $indexName): void {
            $blueprint->index($columns, $indexName);
        });
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
