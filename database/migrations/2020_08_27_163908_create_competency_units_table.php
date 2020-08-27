<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompetencyUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('competency_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schema_id')->onUpdate('cascade')->onDelete('cascade');
            $table->string('code', 255);
            $table->string('title', 255);
            $table->string('standard_type', 255)->default('SKKNI');
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
        Schema::dropIfExists('competency_units');
    }
}
