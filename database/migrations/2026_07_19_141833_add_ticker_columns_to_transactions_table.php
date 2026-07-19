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
    Schema::table('transactions', function (Blueprint $table) {
        $table->string('ticker')->nullable();
        $table->decimal('crypto_amount', 18, 8)->nullable();
    });
}

public function down(): void
{
    Schema::table('transactions', function (Blueprint $table) {
        $table->dropColumn(['ticker', 'crypto_amount']);
    });
}

};
