<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class early_late extends Model
{
    use HasFactory;

    protected $table = 'early_late';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'hours',
        'type',
        'dayOffDate',
        'employeeId',
        'applicationId',
        'createdAt',
        'updatedAt',
    ];

    public $timestamps = false;
}
