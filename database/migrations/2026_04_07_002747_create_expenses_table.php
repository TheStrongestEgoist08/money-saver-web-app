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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('expense_name', 50);
            $table->enum('type', [
                'Food',
                'Groceries',
                'Transportation',
                'Bills',
                'Utilities',
                'Personal Care',
                'Household',
                'Health',
                'Clothing',
                'Entertainment',
                'Education',
                'Savings',
                'Gifts',
                'Maintenance',
                'Subscriptions',
                'Others'
            ]);
            $table->unsignedInteger('quantity')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('total', 10, 2);
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
