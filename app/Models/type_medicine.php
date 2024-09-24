<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class type_medicine extends Model
{
    use HasFactory;

    protected $table = 'type_medicine';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'type',
        'createdAt',
        'updatedAt',
    ];
    
    public $timestamps = false;
}
