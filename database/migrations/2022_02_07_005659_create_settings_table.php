<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('logo')->nullable();
            $table->string('header_background_color')->nullable();
            $table->string('header_text_color')->nullable();
            $table->string('menu_background_color')->nullable();
            $table->string('menu_background_color_hover')->nullable();
            $table->string('menu_icon_color')->nullable();
            $table->string('menu_text_color')->nullable();
            $table->string('menu_border_color')->nullable();
            $table->string('header_text')->nullable();
            $table->string('google_drive_client_id')->nullable();
            $table->string('google_drive_client_secret')->nullable();
            $table->string('google_drive_refresh_token')->nullable();
            $table->string('google_drive_folder_id')->nullable();
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
        Schema::dropIfExists('settings');
    }
}
