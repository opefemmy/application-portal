<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('application_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('application_type_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_type_id')->constrained('application_types')->onDelete('cascade');
            $table->foreignId('form_field_id')->constrained('form_fields')->onDelete('cascade');
            $table->boolean('is_enabled')->default(true);
            $table->boolean('is_required')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['application_type_id', 'form_field_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('application_type_fields');
        Schema::dropIfExists('application_types');
    }
};