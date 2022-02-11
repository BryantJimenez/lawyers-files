<?php

namespace App\Models\Statement;

use Illuminate\Database\Eloquent\Model;

class FileStatement extends Model
{
	protected $table = 'file_statement';

   	protected $fillable = ['name', 'statement_id'];

   	public function statement() {
        return $this->belongsTo(Statement::class);
    }
}
