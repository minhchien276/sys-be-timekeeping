<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class checkin extends Model
{
    use HasFactory;

    protected $table = 'checkin';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'employeeId',
        'checkin',
        'location',
        'latitude',
        'longtitude',
        'meter',
        'createdAt',
        'updatedAt',
    ];
    
    public $timestamps = false;
}
