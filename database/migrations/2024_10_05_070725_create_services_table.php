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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('search_car')->nullable();
            $table->string('current_km')->nullable();
            $table->string('service_duration')->nullable();
            $table->string('service_expense')->nullable();
            $table->date('service_date')->nullable();
            $table->date('next_service_date')->nullable();
            $table->longText('notes')->nullable();
            $table->string('added_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
