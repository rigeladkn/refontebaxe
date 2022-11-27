<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRechargementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rechargements', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 50)->nullable();
            $table->bigInteger('user_id')->unsigned()->index();
            $table->bigInteger('distributeur_id')->unsigned()->index();
            $table->double('montant');

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('distributeur_id')->references('id')->on('distributeurs');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rechargements');
    }
}
