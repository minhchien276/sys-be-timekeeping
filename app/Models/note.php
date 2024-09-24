<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class note extends Model
{
    use HasFactory;

    protected $table = 'note';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'noteName',
        'keyword',
        'createdAt',
        'updatedAt',
    ];
    
    public $timestamps = false;
}
