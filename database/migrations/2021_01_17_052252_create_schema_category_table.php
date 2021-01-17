<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchemaCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schema_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schema_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('schema_category');
    }
}
