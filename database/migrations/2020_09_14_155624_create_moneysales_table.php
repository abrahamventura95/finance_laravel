<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoneysalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('money_sales', function (Blueprint $table) {
            $table->id();
            $table->string('tag')->required();
            $table->string('product')->required();
            $table->integer('quantity')->required();
            $table->enum('type',['income','outflow']);
            $table->float('amount', 8, 2)->required();
            $table->timestamps();

            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            $table->foreignId('coin_id')
                  ->constrained('coins')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('money_sales');
    }
}
