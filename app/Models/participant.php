<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class participant extends Model
{
    use HasFactory;

    protected $table = 'participants';

    protected $primaryKey = 'participantId';

    protected $fillable = [
        'participantId',
        'employeeId',
        'roomId',
        'name',
        'status',
        'createdAt',
        'updatedAt',
    ];

    public $timestamps = false;
}
