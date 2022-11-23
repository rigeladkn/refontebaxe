<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompteBanquesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compte_banques', function (Blueprint $table) {
            $table->id();
            $table->string('num_compte_bancaire')->unique();
            $table->string('nom_banque');
            $table->string('iban');
            $table->string('num_piece_identite');
            $table->string('domiciliation');
        
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
        Schema::dropIfExists('compte_banques');
    }
}
