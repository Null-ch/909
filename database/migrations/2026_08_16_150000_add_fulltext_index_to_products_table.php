<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * FULLTEXT indexes are MySQL/MariaDB-only — SQLite (used locally and in
     * the test suite) has no equivalent, so ProductRepository falls back to
     * a LIKE-based search there. Guarding by driver keeps `migrate` working
     * on every environment this project runs in.
     */
    public function up(): void
    {
        if (! $this->supportsFullText()) {
            return;
        }

        DB::statement('ALTER TABLE products ADD FULLTEXT products_search_fulltext (name, short_description, description)');
    }

    public function down(): void
    {
        if (! $this->supportsFullText()) {
            return;
        }

        Schema::table('products', function ($table) {
            $table->dropFullText('products_search_fulltext');
        });
    }

    private function supportsFullText(): bool
    {
        return in_array(Schema::getConnection()->getDriverName(), ['mysql', 'mariadb'], true);
    }
};
