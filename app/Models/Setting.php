<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['logo', 'header_background_color', 'header_text_color', 'menu_background_color', 'menu_background_color_hover', 'menu_icon_color', 'menu_text_color', 'menu_border_color', 'header_text', 'google_drive_client_id', 'google_drive_client_secret', 'google_drive_refresh_token', 'google_drive_folder_id'];
}
