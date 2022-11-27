<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unique()->unsigned()->index();
            $table->bigInteger('pays_id')->unsigned()->index();
            $table->string('nom');
            $table->string('prenoms');
            $table->string('code_postal');
            $table->string('ville');
            $table->string('email');
            $table->string('telephone');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('pays_id')->references('id')->on('pays');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
