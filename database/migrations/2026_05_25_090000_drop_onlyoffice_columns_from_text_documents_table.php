<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $columnsToDrop = [];
        if (Schema::hasColumn('text_documents', 'document_key')) {
            $columnsToDrop[] = 'document_key';
        }
        if (Schema::hasColumn('text_documents', 'last_synced_at')) {
            $columnsToDrop[] = 'last_synced_at';
        }

        if ($columnsToDrop === []) {
            return;
        }

        Schema::table('text_documents', function (Blueprint $table) use ($columnsToDrop): void {
            $table->dropColumn($columnsToDrop);
        });
    }

    public function down(): void
    {
        Schema::table('text_documents', function (Blueprint $table): void {
            if (!Schema::hasColumn('text_documents', 'document_key')) {
                $table->string('document_key')->nullable()->after('file_path');
            }
            if (!Schema::hasColumn('text_documents', 'last_synced_at')) {
                $table->timestamp('last_synced_at')->nullable()->after('document_key');
            }
        });
    }
};
