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
        Schema::create('promotion_requests', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->text('reason');
            $table->enum('status', ['pending','approved', 'rejected'])->default('pending');
            $table->foreignID('user_id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotion_requests');
    }
};
