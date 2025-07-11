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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // علاقة بالحجز
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');

            // نوع الدفع
            $table->enum('method', ['cash', 'card', 'wallet', 'apple_pay', 'google_pay'])->default('cash');

            // المبلغ المدفوع
            $table->decimal('amount', 8, 2);

            // حالة الدفع
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');

            // رقم العملية/المعامل (للدفع الإلكتروني مثلاً)
            $table->string('transaction_reference')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
