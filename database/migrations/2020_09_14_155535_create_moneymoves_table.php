<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoneymovesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('money_moves', function (Blueprint $table) {
            $table->id();
            //$table->integer('user_id')->unsigned()->required();
            $table->integer('coin_id')->unsigned()->required();
            $table->string('tag')->required();
            $table->enum('type',['income','outflow']);
            $table->float('amount', 8, 2)->required();
            $table->timestamps();

            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            /*$table->foreign('user_id')->references('id')->on('users');
            $table->foreign('coin_id')->references('id')->on('coins');*/
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('money_moves');
    }
}
