<?php

use App\Models\User;
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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users', 'id')->cascadeOnDelete();
            $table->foreignId('account_id')->constrained('accounts', 'id')->cascadeOnDelete();
            $table->unsignedBigInteger('tag_id')->nullable();
            $table->string('name')->nullable();
            $table->string('currency')->default('PHP');
            $table->string('timezone')->default('Asia/Manila');
            $table->unsignedBigInteger('amount');
            $table->unsignedBigInteger('posted_amount')->nullable();
            $table->timestamp('transacted_at');
            $table->timestamp('posted_at')->nullable();
            $table->timestamps();

            // Add Foreign Key constrain to tag_id
            $table->foreign('tag_id')->references('id')->on('tags')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
