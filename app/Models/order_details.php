<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order_details extends Model
{
    use HasFactory;

    protected $table = 'order_details';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'orderId',
        'medicine',
        'typeMedicine',
        'uses',
        'advice',
        'dosage',
        'note',
        'createdAt',
        'updatedAt',
    ];

    public $timestamps = false;

    public function order_medicine()
    {
        return $this->belongsTo(order_medicine::class);
    }
}
