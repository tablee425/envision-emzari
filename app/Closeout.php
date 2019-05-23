<?php

namespace Arrow;

use Illuminate\Database\Eloquent\Model;

class Closeout extends Model
{
    protected $fillable = ['field_id', 'start_date', 'end_date'];

    public function field()
    {
        return $this->belongsTo('App\Field');
    }
}
