<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deposit_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_number', 30)->unique();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('officer_id')->constrained('users')->restrictOnDelete();
            $table->decimal('total_weight_kg', 10, 2)->default(0);
            $table->unsignedInteger('total_points')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index(['officer_id', 'created_at']);
        });

        Schema::create('deposit_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deposit_id')->constrained('deposit_transactions')->cascadeOnDelete();
            $table->foreignId('waste_type_id')->constrained('waste_types')->restrictOnDelete();
            $table->decimal('quantity', 10, 2);
            $table->decimal('quality_multiplier', 3, 2)->default(1.00);
            $table->unsignedInteger('points_per_unit_snapshot');
            $table->unsignedInteger('subtotal_points');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deposit_items');
        Schema::dropIfExists('deposit_transactions');
    }
};
