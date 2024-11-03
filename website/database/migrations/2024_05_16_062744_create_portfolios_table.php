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
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('segment_id');
            $table->foreign('segment_id')->references('id')->on('segments');
            $table->unsignedBigInteger('script_id');
            $table->foreign('script_id')->references('id')->on('scripts');
            $table->enum('auto_close',['0','1']);
            $table->float('total_buy')->default(0);
            $table->float('buy_price')->default(0);
            $table->float('total_sell')->default(0);
            $table->float('sell_price')->default(0);
            $table->float('net_qty')->default(0);
            $table->float('a/b_price')->default(0);
            $table->float('mtm')->default(0);
            $table->integer('limit')->default(0);
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
        Schema::dropIfExists('portfolios');
    }
};
