<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'barcode',
        'price',
        'stock',
        'unit',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }

    public function transactionItems(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }
}

