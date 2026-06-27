<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('text_documents', function (Blueprint $table): void {
            $table->string('file_path')->nullable()->after('content');
            $table->string('document_key')->nullable()->after('file_path');
            $table->timestamp('last_synced_at')->nullable()->after('document_key');
        });
    }

    public function down(): void
    {
        Schema::table('text_documents', function (Blueprint $table): void {
            $table->dropColumn(['file_path', 'document_key', 'last_synced_at']);
        });
    }
};

