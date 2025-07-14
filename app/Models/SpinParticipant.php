<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpinParticipant extends Model
{
    protected $fillable = [
        'full_name',
        'name',
        'city',
        'is_win',
        'fill_style'
    ];
}
