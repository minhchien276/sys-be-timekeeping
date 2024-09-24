<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class overtime extends Model
{
    use HasFactory;

    protected $table = 'overtime';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'employeeId',
        'applicationId',
        'startTime',
        'endTime',
        'dayOffDate',
        'hours',
        'createdAt',
        'updatedAt',
    ];

    public $timestamps = false;
}
