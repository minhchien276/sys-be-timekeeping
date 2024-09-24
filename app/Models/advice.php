<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class advice extends Model
{
    use HasFactory;

    protected $table = 'advice';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'adviceName',
        'createdAt',
        'updatedAt',
    ];
    
    public $timestamps = false;
}
