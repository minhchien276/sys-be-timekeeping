<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class department extends Model
{
    use HasFactory;

    protected $table = 'department';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'name',
        'createdAt',
        'updatedAt',
    ];
    
    public $timestamps = false;
}
