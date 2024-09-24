<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class documentemployee extends Model
{
    use HasFactory;

    protected $table = 'documentemployee';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'employeeId',
        'documentId',
        'status',
        'note',
        'expired',
        'createdAt',
        'updatedAt',
    ];
    
    public $timestamps = false;
}
