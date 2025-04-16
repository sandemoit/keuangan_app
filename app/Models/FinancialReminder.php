<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinancialReminder extends Model
{
    protected $fillable = [
        'name',
        'day_of_month',
        'description',
        'is_active',
        'nominal'
    ];
}
