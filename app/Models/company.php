<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class company extends Model
{
    use HasFactory;

    protected $table = 'company';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'latitude',
        'longtitude',
        'qrCode',
        'createdAt',
        'updatedAt',
    ];
    
    public $timestamps = false;
}
