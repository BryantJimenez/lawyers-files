<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['google_drive_client_id', 'google_drive_client_secret', 'google_drive_refresh_token', 'google_drive_folder_id'];
}
