<?php

namespace App\Traits;

use App\Models\Setting;

trait GeneratesAutomaticCode
{
    public function generateCode($modelClass)
    {
        $model = $modelClass;

        $prefix = Setting::getPrefix() ?? 'FS'; // Define o prefixo padrão
        $currentYear = date('Y');

        // Pega as duas primeiras letras do nome do modelo
        $modelInitials = strtoupper(substr(class_basename($model), 0, 2));

        $codePattern = $prefix.$modelInitials.$currentYear.'-'; // Concatena o prefixo, ano e iniciais do modelo

        $lastBudget = $model::where('code', 'LIKE', $codePattern . '%') // Busca os códigos que começam com o padrão definido
        ->orderBy('id', 'desc')
            ->first();

        $nextCodeNumber = '0001'; // Define o número inicial padrão

        if ($lastBudget) {
            // Extrai a parte numérica após o prefixo, ano e iniciais (ex: se for 'FS24MO0001', pega '0001')
            $lastCode = intval(substr($lastBudget->code, strlen($prefix) + 9)); // +5 para contar os dois dígitos do ano e as iniciais

            // Incrementa o número e preenche com zeros à esquerda até 4 dígitos
            $nextCodeNumber = str_pad($lastCode + 1, 4, '0', STR_PAD_LEFT);
        }

        $nextCode = $prefix . $modelInitials.$currentYear.'-'.$nextCodeNumber; // Concatena prefixo, ano, iniciais e número sequencial

        return $nextCode;
    }

}
