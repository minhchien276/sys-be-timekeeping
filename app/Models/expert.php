<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class expert extends Model
{
    use HasFactory;

    protected $table = 'expert';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'name',
        'phone',
        'image',
        'address',
        'website',
        'createdAt',
        'updatedAt',
    ];
    
    public $timestamps = false;
}
