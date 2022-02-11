<?php

namespace App\Models\Statement;

use App\Models\Company;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Statement extends Model
{
    use SoftDeletes, HasSlug;

    protected $fillable = ['name', 'description', 'type', 'state', 'company_id'];

    /**
     * Get the type.
     *
     * @return string
     */
    public function getTypeAttribute($value)
    {
        if ($value=='1') {
            return 'Caso';
        } elseif ($value=='2') {
            return 'Declaración';
        }
        return 'Desconocido';
    }

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
        $statement=$this->with(['company' => function($query) {
            $query->withTrashed();
        }, 'company.user' => function($query) {
            $query->withTrashed();
        }, 'files'])->where($field, $value)->first();
        if (!is_null($statement)) {
            return $statement;
        }

        return abort(404);
    }

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()->generateSlugsFrom(['name'])->saveSlugsTo('slug')->slugsShouldBeNoLongerThan(191)->doNotGenerateSlugsOnUpdate();
    }

    public function company() {
        return $this->belongsTo(Company::class);
    }

    public function files() {
        return $this->hasMany(FileStatement::class);
    }
}
