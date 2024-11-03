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
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('segment_id');
            $table->unsignedBigInteger('script_id');
            $table->integer('quantity');
            $table->decimal('entry_price', 15, 2);
            $table->decimal('current_price', 15, 2);
            $table->date('entry_date');
            $table->date('expiry_date')->nullable();
            $table->enum('position_type', ['long', 'short']);
            $table->enum('status', ['open', 'closed']);
            $table->decimal('profit_loss', 15, 2)->nullable();
            $table->decimal('stop_loss', 15, 2)->nullable();
            $table->decimal('target_price', 15, 2)->nullable();
            $table->string('broker')->nullable();
            $table->decimal('transaction_fee', 15, 2)->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('segment_id')->references('id')->on('segments')->onDelete('cascade');
            $table->foreign('script_id')->references('id')->on('scripts')->onDelete('cascade');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->softdeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
