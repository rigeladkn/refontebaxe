<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNumCompteBancaireToDemandeDistributeurs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('demande_distributeurs', function (Blueprint $table) {
            $table->string('num_compte_bancaire', 50)->after('registre_commerce')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('demande_distributeurs', function (Blueprint $table) {
            //
        });
    }
}
