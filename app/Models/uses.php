<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class uses extends Model
{
    use HasFactory;

    protected $table = 'uses';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'usesName',
        'createdAt',
        'updatedAt',
    ];
    
    public $timestamps = false;
}
