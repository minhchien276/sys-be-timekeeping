<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class task_participant extends Model
{
    use HasFactory;

    protected $table = 'task_participants';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'taskId',
        'participantId',
        'expired',
        'status',
        'note',
        'createdAt',
        'updatedAt',
    ];

    public $timestamps = false;
}
