<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class checkout extends Model
{
    use HasFactory;

    protected $table = 'checkout';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'employeeId',
        'checkout',
        'location',
        'latitude',
        'longtitude',
        'meter',
        'createdAt',
        'updatedAt',
    ];
    
    public $timestamps = false;
}
