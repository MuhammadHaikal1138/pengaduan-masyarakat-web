<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    // protected $fillable = [
    //     'user_id',
    //     'description',
    //     'type',
    //     'province',
    //     'regency',
    //     'subdistrict',
    //     'village',
    //     'voting',
    //     'viewers',
    //     'image',
    //     'statement',
    // ];

    protected $guarded = [''];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function response()
    {
        return $this->hasOne(Response::class);
    }

    // Report.php
    public function likes()
{
    return $this->belongsToMany(User::class, 'report_likes');
}


}
