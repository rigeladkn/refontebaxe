<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RendreLaColonnePieceIdentiteNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('distributeurs', function (Blueprint $table) {
            $table->json('path_piece_identitite')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('distributeurs', function (Blueprint $table) {
            $table->json('path_piece_identitite')->change();
        });
    }
}
