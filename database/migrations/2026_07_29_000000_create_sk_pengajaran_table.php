<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSkPengajaranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sk_pengajaran', function (Blueprint $table) {
            $table->id('id_sk_pengajaran');
            $table->integer('id_periodetahun');
            $table->integer('id_periodetipe');
            $table->string('kodeprodi', 50);
            $table->string('file', 255);
            $table->string('status', 20)->default('ACTIVE');
            $table->string('created_by', 100)->nullable();
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
        Schema::dropIfExists('sk_pengajaran');
    }
}
