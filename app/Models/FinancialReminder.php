<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinancialReminder extends Model
{
    protected $fillable = [
        'name',
        'category_id',
        'day_of_month',
        'description',
        'is_active',
        'nominal'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
