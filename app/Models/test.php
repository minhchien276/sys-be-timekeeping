<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class test extends Model
{
    use HasFactory;

    protected $table = 'tests';

    protected $primaryKey = 'testId';

    protected $fillable = [
        'testId',
        'title',
        'description',
        'totalMarks',
        'timeLimit',
        'createdAt',
        'updatedAt',
    ];
    
    public $timestamps = false;
}
