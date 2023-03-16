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
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('service_id');
            $table->uuid('user_id');
            $table->uuid('children_id');
            $table->date('order_at');
            $table->text('note')->nullable();
            $table->string('status');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['service_id', 'user_id', 'children_id', 'status']);

            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('children_id')->references('id')->on('childrens');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
