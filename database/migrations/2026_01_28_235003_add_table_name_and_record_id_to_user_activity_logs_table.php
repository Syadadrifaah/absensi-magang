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
        Schema::table('user_activity_logs', function (Blueprint $table) {
            //
            $table->string('table_name')->nullable()->after('action');
            $table->unsignedBigInteger('record_id')->nullable()->after('table_name');
            
            $table->index(['table_name', 'record_id']);
            $table->index('action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_activity_logs', function (Blueprint $table) {
            //
            $table->dropIndex(['table_name', 'record_id']);
            $table->dropIndex(['action']);
            $table->dropColumn(['table_name', 'record_id']);
        });
    }
};
