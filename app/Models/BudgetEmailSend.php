<?php

namespace App\Models;

use App\Filament\Resources\BudgetResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BudgetEmailSend extends Model
{
    protected $guarded = [];

    public function budget():BelongsTo
    {
        return $this->belongsTo(Budget::class, 'budget_id', 'id');
    }

}
