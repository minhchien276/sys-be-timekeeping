<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class answer extends Model
{
    use HasFactory;

    protected $table = 'answers';

    protected $primaryKey = 'answerId';

    protected $fillable = [
        'answerId',
        'questionId',
        'answerText',
        'isCorrect',
        'createdAt',
        'updatedAt',
    ];

    public $timestamps = false;
}
