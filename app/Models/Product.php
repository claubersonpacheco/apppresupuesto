<?php

namespace App\Models;

use App\Enum\ProductType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $guarded;

    protected $casts = [
        'product_type' => ProductType::class
    ];

    public function category():BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function budget():BelongsTo
    {
        return $this->belongsTo(Budget::class);
    }

    public function items():HasMany
    {
        return $this->hasMany(BudgetItem::class);
    }
}
