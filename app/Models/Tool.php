<?php

namespace App\Models;

use Filament\AvatarProviders\UiAvatarsProvider;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tool extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'category_id',
        'brand',
        'model',
        'serial_number',
        'condition',
        'purchase_date',
        'purchase_price',
        'invoice',
        'notes',
        'photo_path',
    ];

    public function category():BelongsTo
    {
        return $this->belongsTo(ToolCategory::class);
    }

    public function getFilamentAvatarUrl(): ?string
    {

        if (empty($this->avatar))
            return (new UiAvatarsProvider())->get($this);

        return asset('storage/' . $this->photo_path);

    }
}
