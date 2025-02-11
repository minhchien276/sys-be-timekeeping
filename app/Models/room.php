<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class room extends Model
{
    use HasFactory;

    protected $table = 'rooms';

    protected $primaryKey = 'roomId';

    protected $fillable = [
        'roomId',
        'employeeId',
        'name',
        'createdAt',
        'updatedAt',
    ];

    public $timestamps = false;
}
