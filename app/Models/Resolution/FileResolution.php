<?php

namespace App\Models\Resolution;

use Illuminate\Database\Eloquent\Model;

class FileResolution extends Model
{
	protected $table = 'file_resolution';

   	protected $fillable = ['name', 'resolution_id'];

   	public function resolution() {
        return $this->belongsTo(Resolution::class);
    }
}
