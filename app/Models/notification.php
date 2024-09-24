<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class notification extends Model
{
    use HasFactory;

    protected $table = 'notification';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'notiTitle',
        'notiContent',
        'receiverId',
        'senderId',
        'applicationId',
        'type',
        'seen',
        'createdAt',
        'updatedAt',
    ];

    public $timestamps = false;
}
