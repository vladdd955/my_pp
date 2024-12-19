<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $table = 'tasks';

    protected $fillable = [
        'title',
        'status',
        'user_id',
        'description',
    ];

    public $timestamps = true;

    public static function getTaskById(int $id): Task
    {
        return self::where('id', $id)->first();
    }


}
