<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('interviews', function (Blueprint $table) {
            if (!Schema::hasColumn('interviews', 'interview_time')) {
                $table->time('interview_time')->nullable()->after('interview_date');
            }
            if (!Schema::hasColumn('interviews', 'panel')) {
                $table->string('panel')->nullable()->after('interview_time');
            }
            if (!Schema::hasColumn('interviews', 'meeting_link')) {
                $table->string('meeting_link')->nullable()->after('panel');
            }
        });
    }

    public function down(): void
    {
        Schema::table('interviews', function (Blueprint $table) {
            $table->dropColumn(['interview_time', 'panel', 'meeting_link']);
        });
    }
};