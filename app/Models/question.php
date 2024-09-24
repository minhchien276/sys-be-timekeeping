<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class question extends Model
{
    use HasFactory;

    protected $table = 'questions';

    protected $primaryKey = 'questionId';

    protected $fillable = [
        'questionId',
        'testId',
        'questionText',
        'marks',
        'url',
        'type',
        'createdAt',
        'updatedAt',
    ];

    public $timestamps = false;
}
