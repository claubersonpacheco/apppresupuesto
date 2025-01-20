<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Budget extends Model
{
    use HasFactory;

    protected $guarded;


    public function statusHistories()
    {
        return $this->hasMany(StatusHistory::class);
    }

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customer():BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function products():HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(BudgetItem::class);
    }

    protected static function booted()
    {
        static::created(function ($budget) {
            \App\Models\StatusHistory::create([
                'budget_id' => $budget->id,
                'status' => \App\Models\StatusHistory::STATUS_OPEN, // Aberto
                'changed_by' => auth()->id(),
            ]);
        });
    }

    public function latestStatus()
    {
        return $this->hasOne(StatusHistory::class)->latestOfMany();
    }

    public function serviceProviders()
    {
        return $this->hasMany(ServiceProvider::class);
    }

    public function productSuppliers()
    {
        return $this->hasMany(ProductSupplier::class);
    }
}
