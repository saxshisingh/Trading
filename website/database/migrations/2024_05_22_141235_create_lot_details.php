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
        Schema::create('lot_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('segment_id');
            $table->unsignedBigInteger('script_id');
            $table->unsignedBigInteger('expiry_date_id');
            $table->integer('lot_quantity');
            $table->foreign('segment_id')->references('id')->on('segments')->onDelete('cascade');
            $table->foreign('script_id')->references('id')->on('scripts')->onDelete('cascade');
            $table->foreign('expiry_date_id')->references('id')->on('expiry_date');
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
        Schema::dropIfExists('lot_details');
    }
};
