<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class typeofwork extends Model
{
    use HasFactory;

    protected $table = 'typeofwork';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'name',
        'timeIn',
        'timeOut',
        'description',
        'createdAt',
        'updatedAt',
    ];
    
    public $timestamps = false;
}
