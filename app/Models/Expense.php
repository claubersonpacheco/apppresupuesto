<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    use HasFactory;

    protected $guarded;

    public function customer():BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id'); // Verifique se o nome do relacionamento está correto
    }
}
