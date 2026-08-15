<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_method_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('name')->nullable();
            $table->decimal('min_weight', 8, 2)->default(0);
            $table->decimal('max_weight', 8, 2)->nullable();
            $table->decimal('min_volume', 8, 4)->default(0);
            $table->decimal('max_volume', 8, 4)->nullable();
            $table->decimal('max_length', 8, 2)->nullable();
            $table->decimal('max_width', 8, 2)->nullable();
            $table->decimal('max_height', 8, 2)->nullable();
            $table->decimal('price', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['delivery_method_id', 'is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_rates');
    }
};
