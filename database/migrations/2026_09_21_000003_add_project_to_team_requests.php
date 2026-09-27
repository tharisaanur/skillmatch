<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('team_requests', function (Blueprint $table) {
            $table->foreignId('project_id')->nullable()->after('recipient_id')->constrained()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('team_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('project_id');
        });
    }
};
