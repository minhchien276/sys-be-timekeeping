<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class salary extends Model
{
    use HasFactory;

    protected $table = 'salary';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'employeeId',
        'workDay',
        'less5m',
        'more5m',
        'dayMissing',
        'dayOff',
        'dayOffLeft',
        'salary',
        'bonus',
        'otherBonus',
        'CK',
        'bonusByMonth',
        'insurancePrice',
        'total',
        'punishPrice',
        'drugPrice',
        'errorOrderPrice',
        'refundPrice',
        'discountRefund',
        'discountKeeping',
        'responseDeadline',
        'responseContent',
        'createdAt',
        'updatedAt',
    ];

    public $timestamps = false;
}
