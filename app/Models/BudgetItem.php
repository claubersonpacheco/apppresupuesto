<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BudgetItem extends Model
{
    use HasFactory;

    protected $fillable = ['budget_id', 'product_id', 'quantity', 'price', 'tax', 'description', 'total', 'total_tax'];

    public function budget():BelongsTo
    {
        return $this->belongsTo(Budget::class);
    }

    public function product():BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function printMarks()
    {
        return $this->hasMany(BudgetItemToPrint::class);
    }



}
