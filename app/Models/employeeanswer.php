<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class employeeanswer extends Model
{
    use HasFactory;

    protected $table = 'employeeanswers';

    protected $primaryKey = 'employeeAnswerId';

    protected $fillable = [
        'employeeAnswerId',
        'employeeId',
        'testId',
        'questionId',
        'selectedAnswerId',
        'inputAnswer',
        'score',
        'createdAt',
        'updatedAt',
    ];

    public $timestamps = false;
}
