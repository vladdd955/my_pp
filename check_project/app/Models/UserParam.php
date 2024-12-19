<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserParam extends Model
{
    use HasFactory;


    protected $table = 'user_param';

    protected $fillable = [
        'param',
        'value',
        'user_id'
    ];
}
