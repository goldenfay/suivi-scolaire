<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParametresSMSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parametres_sms', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('senderId');
            $table->string('host');
            $table->string('port');
            $table->string('username')->unique();
            $table->string('password')->unique();
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
        Schema::dropIfExists('parametres_sms');
    }
}
