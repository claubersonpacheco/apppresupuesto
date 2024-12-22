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
        Schema::create('budget_items_to_print', function (Blueprint $table) {
            $table->id();
            $table->foreignId('budget_item_id')->constrained()->onDelete('cascade'); // Relaciona com os itens do orçamento
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_items_to_print');
    }
};
