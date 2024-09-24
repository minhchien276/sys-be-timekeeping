<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class timesheet extends Model
{
    use HasFactory;

    protected $table = 'timesheet';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'workday',
        'less5m',
        'more5m',
        'paidLeave',
        'unpaidLeave',
        'earlyDay',
        'otHours',
        'otOver3h',
        'otOver5h',
        'createdAt',
        'updatedAt',
    ];
    
    public $timestamps = false;
}
