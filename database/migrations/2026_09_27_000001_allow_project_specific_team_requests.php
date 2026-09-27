<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('team_requests', function (Blueprint $table) {
            $table->unique(['sender_id', 'project_id']);
        });
        Schema::table('team_requests', function (Blueprint $table) {
            $table->dropUnique('team_requests_sender_id_recipient_id_unique');
        });
    }

    public function down(): void
    {
        Schema::table('team_requests', function (Blueprint $table) {
            $table->unique(['sender_id', 'recipient_id']);
        });
        Schema::table('team_requests', function (Blueprint $table) {
            $table->dropUnique('team_requests_sender_id_project_id_unique');
        });
    }
};
