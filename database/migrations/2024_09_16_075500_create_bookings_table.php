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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id(); // Primary key
            // $table->tinyInteger('booking_type')->comment('1 for booking, 2 for reservation'); // Booking type
            $table->string('booking_no')->nullable(); // Booking number
            $table->integer('customer_id')->nullable(); // Foreign key to customers table
            $table->date('booking_date')->nullable(); // Booking date
            $table->date('start_date')->nullable(); // Rent date
            $table->date('end_date')->nullable(); // Return date
            $table->time('start_time')->nullable(); // Retime
            $table->time('end_time')->nullable(); // Retime date
            $table->integer('duration')->nullable(); // Duration in days
            $table->integer('car_id')->nullable(); // Dress name (foreign key or integer reference)
            $table->decimal('price', 8, 3)->nullable(); // Price with 3 digits after the decimal
            $table->decimal('discount', 8, 2)->nullable(); // Discount with 2 digits after the decimal (nullable)
            $table->decimal('total_price', 8, 3)->nullable(); // Total price with 3 digits after the decimal
            $table->longText('attributes')->nullable();
            $table->longText('extras')->nullable();
            $table->integer('pickup')->nullable();
            $table->integer('dropoff')->nullable();
            $table->longText('notes')->nullable()->nullable(); // Notes (optional)
            $table->tinyInteger('status')->default(1)->comment('1  for rented, 2 for finished, 3 for Cancel'); // Booking status
            $table->integer('user_id')->nullable(); // Foreign key to users table (nullable)
            $table->string('added_by')->nullable(); // User who added the booking
            $table->string('updated_by')->nullable(); // User who updated the booking
            $table->timestamps(); // created_at and up
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
