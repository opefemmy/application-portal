<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('form_fields', function (Blueprint $table) {
            $table->id();
            $table->string('section');
            $table->string('field_name');
            $table->string('field_label');
            $table->string('field_type');
            $table->json('options')->nullable();
            $table->boolean('is_visible')->default(true);
            $table->boolean('is_required')->default(false);
            $table->integer('sort_order')->default(0);
            $table->json('validation_rules')->nullable();
            $table->timestamps();

            $table->unique(['section', 'field_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('form_fields');
    }
};