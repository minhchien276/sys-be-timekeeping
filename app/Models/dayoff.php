<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dayoff extends Model
{
    use HasFactory;

    protected $table = 'dayoff';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'employeeId',
        'dayOffDate',
        'session',
        'type',
        'applicationId',
        'createdAt',
        'updatedAt',
    ];
    
    public $timestamps = false;
}
