<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_prof_telegram', function (Blueprint $table) {
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('prof_id');
            $table->string('telegram_channel')->nullable();
            $table->timestamps();
    
            $table->foreign('class_id')->references('id')->on('classe')->onDelete('cascade');
            $table->foreign('prof_id')->references('id')->on('professeur')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('class_prof_telegram');
    }
};
