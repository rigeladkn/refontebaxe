<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistributeursTable extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        /* Nom et Prénom(s)
        Localisation
        Pays
        Ville
        Quelle est votre activité principale ?
        Est ce qu’ils ont un document  qui prouve qu’ils sont constitués en entreprise
        Possibilité d’uploader le document
        Ou avez vous entendu parler de Devenir Agent Baxe Money
        Choix de la pièce d’identité
        Scanner sa pièce d’identité
        Photo ou vidéo du local */


        Schema::create('distributeurs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unique()->unsigned()->index();
            $table->bigInteger('pays_id')->unsigned()->index();
            $table->string('nom');
            $table->string('prenoms');
            $table->string('code_postal');
            $table->string('ville');
            $table->string('email');
            $table->string('telephone');
            $table->string('telephone2')->nullable();
            $table->string('telephone3')->nullable();
            $table->string('activite_principale');
            $table->string('registre_commerce')->nullable();
            $table->string('entreprise_nom');
            $table->json('path_piece_identitite');
            $table->json('path_media_du_local')->nullable();
            $table->string('communication_baxe')->nullable();
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
        Schema::dropIfExists('distributeurs');
    }
}
