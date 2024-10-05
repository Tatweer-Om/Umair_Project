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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name')->nullable(); // Customer name
            $table->string('customer_email')->nullable(); // Customer email (unique)
            $table->string('customer_number')->nullable(); // Phone number
            $table->tinyInteger('doc_type')->nullable()->comment('1 for id_card, 2 for passport'); // Gender as integer
            $table->date('dob')->nullable(); // Date of birth (nullable)
            $table->string('id_no')->nullable(); // Customer name
            $table->date('id_expiry')->nullable(); // Customer email (unique)
            $table->string('license_no')->nullable(); // Phone number
            $table->date('license_expiry')->nullable(); // Customer email (unique)
            $table->text('address')->nullable(); // Address (optional)
            $table->tinyInteger('status')->default(1)->comment('1 for active, 2 for disable'); // Status with default value
            $table->integer('user_id')->nullable(); // Foreign key to users table (nullable)
            $table->string('added_by')->nullable(); // User who added the booking
            $table->string('updated_by')->nullable(); // User who updated the booking
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
