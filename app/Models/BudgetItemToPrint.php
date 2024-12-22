<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BudgetItemToPrint extends Model
{
    use HasFactory;

    protected $guarded;

    public function budgetItem()
    {
        return $this->belongsTo(BudgetItem::class);
    }
}
