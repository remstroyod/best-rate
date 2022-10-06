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
        Schema::create('parses', function (Blueprint $table)
        {

            $table->increments('id');
            $table->integer('ident')->index()->unique();
            $table->integer('start_exchange')->default(0);
            $table->integer('end_exchanhe')->default(0);
            $table->double('start_rate', 20, 2)->default(0);
            $table->double('end_rate', 20, 2)->default(0);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parses');
    }
};
