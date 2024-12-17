<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResponseProgress extends Model
{
    protected $fillable = [
        'report_id',
        'histories',
    ];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function response()
    {
        return $this->belongsTo(Response::class);
    }

}
