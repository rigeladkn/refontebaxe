<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDemandeDistributeursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demande_distributeurs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('pays_register_id');
            $table->string('ip_register');
            $table->string('nom');
            $table->string('prenoms');
            $table->string('code_postal')->nullable();
            $table->string('ville');
            $table->string('email')->unique();
            $table->string('telephone')->unique();
            $table->string('telephone2')->nullable();
            $table->string('telephone3')->nullable();
            $table->string('activite_principale');
            $table->string('entreprise_nom')->nullable();
            $table->string('registre_commerce')->nullable();
            $table->json('path_piece_identite')->nullable();
            $table->json('path_media_du_local')->nullable();
            $table->string('communication_baxe')->nullable();
            $table->string('recent_ip');

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
        Schema::dropIfExists('demande_distributeurs');
    }
}
