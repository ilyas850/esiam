<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKonversiMakulTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('konversi_makul')) {
            Schema::create('konversi_makul', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('id_makul_awal', 15)->nullable();
                $table->string('id_makul_baru', 15)->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('konversi_makul');
    }
}
