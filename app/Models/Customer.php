<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $guarded;

    public function budgets():HasMany
    {
        return $this->hasMany(Budget::class);
    }

    public function expenses():HasMany
    {
        return $this->hasMany(Expense::class);
    }

}
