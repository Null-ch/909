<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->text('description')->nullable()->after('sku');
            $table->text('short_description')->nullable()->after('description');
            $table->decimal('price', 10, 2)->default(0)->after('short_description');
            $table->decimal('old_price', 10, 2)->nullable()->after('price');
            $table->unsignedInteger('quantity')->default(0)->after('old_price');
            $table->boolean('is_active')->default(true)->after('quantity');
            $table->boolean('is_featured')->default(false)->after('is_active');
            $table->decimal('weight', 8, 2)->nullable()->after('is_featured');
            $table->string('meta_title')->nullable()->after('weight');
            $table->text('meta_description')->nullable()->after('meta_title');

            $table->index('is_active');
            $table->index('is_featured');
            $table->index('price');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
            $table->dropIndex(['is_featured']);
            $table->dropIndex(['price']);
            $table->dropColumn([
                'description',
                'short_description',
                'price',
                'old_price',
                'quantity',
                'is_active',
                'is_featured',
                'weight',
                'meta_title',
                'meta_description',
            ]);
        });
    }
};
