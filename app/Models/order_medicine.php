<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order_medicine extends Model
{
    use HasFactory;

    protected $table = 'order_medicine';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'title',
        'expertId',
        'note',
        'qrCode',
        'createdAt',
        'updatedAt',
    ];

    public $timestamps = false;

    public function expert()
    {
        return $this->belongsTo(expert::class, 'expertId');
    }

    public function getFormattedCreatedAtAttribute()
    {
        return $this->attributes['createdAt']
            ? Carbon::createFromTimestampMs($this->attributes['createdAt'])->format('d-m-Y H:i:s')
            : null;
    }

    public function order_details()
    {
        return $this->hasMany(order_details::class);
    }
}
