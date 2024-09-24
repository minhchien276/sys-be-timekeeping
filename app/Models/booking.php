<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class booking extends Model
{
    use HasFactory;

    protected $table = 'booking';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'timeIn',
        'timeOut',
        'departmentId',
        'status',
        'createdAt',
        'updatedAt',
    ];
    
    public $timestamps = false;
}
