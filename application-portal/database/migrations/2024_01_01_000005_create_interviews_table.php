<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('applications')->cascadeOnDelete();
            $table->string('venue');
            $table->date('interview_date');
            $table->time('interview_time');
            $table->string('panel');
            $table->string('meeting_link')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('application_id');
            $table->index('interview_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interviews');
    }
};