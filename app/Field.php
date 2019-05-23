<?php

namespace Arrow;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['area_id', 'name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function locations()
    {
        return $this->hasMany('Arrow\Location');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function closeOuts()
    {
        return $this->hasMany('Arrow\Closeout');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function area()
    {
        return $this->belongsTo('Arrow\Area');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function injections()
    {
        return $this->hasManyThrough('Arrow\Injection', 'Arrow\Location');
    }
}
