<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    protected $fillable = [
        'report_id',
        'response_status',
        'staff_id',
    ];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function staff()
    {
        return $this->belongsTo(User::class);
    }

}
