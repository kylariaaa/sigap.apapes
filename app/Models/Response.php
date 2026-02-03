<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    protected $guarded = [];

    Public function user()
    {
        return $this->belongsTo(User::class);
    }

    //tanggapan milik laporan yang mana
    Public function report()
    {
        return $this->belongsTo(Report::class);
    }
}
