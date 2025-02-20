<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\AvatarProviders\UiAvatarsProvider;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\HasTenants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Panel;
use Illuminate\Queue\Connectors\BeanstalkdConnector;
use Illuminate\Support\Collection;

class User extends Authenticatable  implements FilamentUser, HasAvatar, HasTenants
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'code',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getFilamentAvatarUrl(): ?string
    {

        if (empty($this->avatar))
            return (new UiAvatarsProvider())->get($this);

        return asset('storage/' . $this->avatar);

    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function getTenants(Panel $panel):Collection
    {
        return $this->tenants;
    }

    public function canAccessTenant(Model $tenant): bool
    {
        return $this->tenants()->contains($tenant);
    }

    public function tenant():BelongsTo
    {
        return $this->belongsTo(Tenant::class);

    }
}
