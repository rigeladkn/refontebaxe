<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommissionModalitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commission_modalites', function (Blueprint $table) {
            $table->id();
            $table->string('operation');
            $table->string('continent');
            $table->string('pays')->nullable();
            $table->string('from');
            $table->string('to');
            $table->double('frais_pourcentage')->nullable();
            $table->double('frais_fixe')->nullable();
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
        Schema::dropIfExists('commission_modalites');
    }
}
