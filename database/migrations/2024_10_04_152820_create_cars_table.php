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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->integer('model_name')->nullable();
            $table->integer('brand_name')->nullable();
            $table->integer('color_name')->nullable();
            $table->integer('year_name')->nullable();
            $table->integer('insurance_company')->nullable();
            $table->date('insurance_expiry_date')->nullable();
            $table->string('plate_no')->nullable();
            $table->string('chassis_no')->nullable();
            $table->decimal('per_day_price', 50, 3)->nullable();
            $table->decimal('per_week_price', 50, 3)->nullable();
            $table->decimal('per_month_price', 50, 3)->nullable();
            $table->date('mulkia_expiry_date')->nullable();
            $table->date('trans_min_expiry')->nullable();
            $table->date('vms_expiry')->nullable();
            $table->longText('notes')->nullable();
            $table->string('car_image')->nullable();
            $table->string('status')->default('1')->comment('1 for default, 2 for under_maint');
            $table->string('added_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('user_id', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
