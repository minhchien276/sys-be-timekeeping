<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class application extends Model
{
    use HasFactory;

    protected $table = 'application';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'title',
        'content',
        'image',
        'type',
        'status',
        'employeeId',
        'approverId',
        'createdAt',
        'updatedAt',
    ];
    
    public $timestamps = false;
}
