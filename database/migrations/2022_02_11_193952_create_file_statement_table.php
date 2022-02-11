<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileStatementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_statement', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('statement_id')->unsigned()->nullable();
            $table->timestamps();

            #Relations
            $table->foreign('statement_id')->references('id')->on('statements')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('file_statement');
    }
}
