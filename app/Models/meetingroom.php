<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class meetingroom extends Model
{
    use HasFactory;

    protected $table = 'meetingroom';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'number',
        'name',
        'status',
        'createdAt',
        'updatedAt',
    ];
    
    public $timestamps = false;
}
