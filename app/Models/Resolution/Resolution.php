<?php

namespace App\Models\Resolution;

use App\Models\Statement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Resolution extends Model
{
    use SoftDeletes, HasSlug;

    protected $fillable = ['name', 'slug', 'description', 'date', 'statement_id'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'datetime'
    ];

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        $resolution=$this->with(['statement' => function($query) {
            $query->withTrashed();
        }, 'statement.company' => function($query) {
            $query->withTrashed();
        }, 'statement.company.user' => function($query) {
            $query->withTrashed();
        }, 'files'])->where($field, $value)->first();
        if (!is_null($resolution)) {
            return $resolution;
        }

        return abort(404);
    }

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom(['name'])->saveSlugsTo('slug')->slugsShouldBeNoLongerThan(191)->doNotGenerateSlugsOnUpdate();
    }

    public function statement() {
        return $this->belongsTo(Statement::class);
    }

    public function files() {
        return $this->hasMany(FileResolution::class);
    }
}
