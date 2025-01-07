<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusHistory extends Model
{
    use HasFactory;

    protected $guarded;

    const STATUS_OPEN = 1;
    const STATUS_SENT = 2;
    const STATUS_PENDING = 3;
    const STATUS_REJECTED = 4;
    const STATUS_APPROVED = 5;
    const STATUS_IN_PROCESS = 6;
    const STATUS_COMPLETED = 7;


    public static function getStatusOptions()
    {
        return [
            self::STATUS_OPEN => 'Abierto',
            self::STATUS_SENT => 'Enviado',
            self::STATUS_PENDING => 'Pendiente',
            self::STATUS_REJECTED => 'Rechazado',
            self::STATUS_APPROVED => 'Aprobado',
            self::STATUS_IN_PROCESS => 'En proceso',
            self::STATUS_COMPLETED => 'Finalizado',
        ];
    }

    public function getStatusLabelAttribute()
    {
        return self::getStatusOptions()[$this->status] ?? 'Unknown';
    }

    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'changed_by'); // Supondo que 'changed_by' seja o campo que armazena o ID do usuário
    }
}
