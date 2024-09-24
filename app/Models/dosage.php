<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dosage extends Model
{
    use HasFactory;

    protected $table = 'dosage';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'dosageName',
        'createdAt',
        'updatedAt',
    ];
    
    public $timestamps = false;
}
