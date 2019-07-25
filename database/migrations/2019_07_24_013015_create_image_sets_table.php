<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImageSetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('image_sets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('row')->nullable();
            $table->integer('cover_image_id')->default(0);
            $table->integer('image_count')->default(0);
            $table->integer('thumbup_count')->default(0);
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
        Schema::dropIfExists('image_sets');
    }
}
