<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->foreignId('wallet_id')
                ->nullable()
              ->constrained('wallets')
              ->onDelete('set null');

            $table->enum('type', [
                'Balance Added',
                'Expense',
                'Transfer',
                'Wallet Created',
                'Wallet Deleted',
            ])->default('Expense');

            $table->decimal('amount', 15, 2)->default(0.00);

            $table->string('description')->nullable();

            $table->json('metadata')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'type']);
            $table->index(['wallet_id', 'type']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
