<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class task extends Model
{
    use HasFactory;

    protected $table = 'tasks';

    protected $primaryKey = 'taskId';

    protected $fillable = [
        'taskId',
        'title',
        'content',
        'expired',
        'employeeId',
        'roomId',
        'createdAt',
        'updatedAt',
    ];

    public $timestamps = false;
}
