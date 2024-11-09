<?php

namespace App\Enum;

enum ProductType: string
{
    case UNIDADE = 'unidade';
    case METROS = 'metro';
    case CENTIMETROS = 'centimetro';
    case LITROS = 'litros';

    case DIA = 'dia';

    case HORA = 'hora';

    case MINUTO = 'minuto';
}
