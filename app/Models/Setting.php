<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $guarded;

    public static function getPrefix()
    {
        // Certifique-se de que existe um registro com o prefixo
        $setting = self::first(); // ou algum critério específico

        if ($setting) {
            return $setting->prefix;
        }

        // Retorne um valor padrão caso não encontre
        return 'FS';
    }
}
