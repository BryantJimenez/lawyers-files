<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileResolutionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_resolution', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('resolution_id')->unsigned()->nullable();
            $table->timestamps();

            #Relations
            $table->foreign('resolution_id')->references('id')->on('resolutions')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('file_resolution');
    }
}
