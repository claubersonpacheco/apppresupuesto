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
        Schema::create('status_histories', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('budget_id'); // Relacionado ao orçamento
            $table->foreign('budget_id')->references('id')->on('budgets')->onDelete('cascade');

            $table->tinyInteger('status')->default(1); // O status (e.g., 1 = aberto, 2 = enviado)
            $table->text('comments')->nullable(); // Comentários opcionais sobre a mudança

            $table->unsignedBigInteger('changed_by')->nullable(); // Usuário que fez a alteração
            $table->foreign('changed_by')->references('id')->on('users')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status_histories');
    }
};
