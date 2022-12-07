<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPaymentAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_payment_accounts', function (Blueprint $table) {
            $table->id();
            $table->string("bank_name");
            $table->char("number",16);
            $table->foreignId("user_id")->constrained("users")
            ->onUpdate("cascade")
            ->onDelete("cascade");;
            $table->boolean("status")->default(1);
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
        Schema::dropIfExists('user_payment_accounts');
    }
}
