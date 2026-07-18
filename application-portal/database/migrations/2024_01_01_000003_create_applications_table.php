<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('application_number')->unique();
            $table->json('personal_info');
            $table->json('academic_info');
            $table->json('employment_info')->nullable();
            $table->json('application_details');
            $table->enum('status', ['pending', 'reviewed', 'shortlisted', 'interview_scheduled', 'accepted', 'rejected', 'completed'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('application_number');
            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};