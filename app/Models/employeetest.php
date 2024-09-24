<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class employeetest extends Model
{
    use HasFactory;

    protected $table = 'employeetests';

    protected $primaryKey = 'employeeTestId';

    protected $fillable = [
        'employeeTestId',
        'employeeId',
        'testId',
        'scoreChoice',
        'scoreEssay',
        'startTime',
        'endTime',
        'pause',
        'expired',
        'createdAt',
        'updatedAt',
    ];

    public $timestamps = false;
}
