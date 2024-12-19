<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResponseProgress extends Model
{
    protected $fillable = [
        'response_id',
        'histories',
    ];


    public function response()
    {
        return $this->belongsTo(Response::class);
    }

}
