<?php

namespace App\Models;

use App\Models\Resolution\Resolution;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Statement extends Model
{
    use SoftDeletes, HasSlug;

    protected $fillable = ['name', 'slug', 'description', 'state', 'type_id', 'company_id'];

    /**
     * Get the state.
     *
     * @return string
     */
    public function getStateAttribute($value)
    {
        if ($value=='1') {
            return 'Activo';
        } elseif ($value=='0') {
            return 'Inactivo';
        }
        return 'Desconocido';
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        $statement=$this->with(['type' => function($query) {
            $query->withTrashed();
        }, 'company' => function($query) {
            $query->withTrashed();
        }, 'company.user' => function($query) {
            $query->withTrashed();
        }, 'resolutions.files'])->where($field, $value)->first();
        if (!is_null($statement)) {
            return $statement;
        }

        return abort(404);
    }

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom(['name'])->saveSlugsTo('slug')->slugsShouldBeNoLongerThan(191)->doNotGenerateSlugsOnUpdate();
    }

    public function type() {
        return $this->belongsTo(Type::class);
    }

    public function company() {
        return $this->belongsTo(Company::class);
    }

    public function resolutions() {
        return $this->hasMany(Resolution::class);
    }
}
