<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestData extends Model
{
    protected $fillable = [
        'nama',
        'alamat',
        'no_wa',
        'is_win',
        'fill_style'
    ];
}
