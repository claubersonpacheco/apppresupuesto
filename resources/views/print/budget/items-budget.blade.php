<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Presupuesto - {{ $budget->customer->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        h3, th {
            font-size: 14px;
        }

        h2 {
            font-size: 13px;
        }

        p, td {
            font-size: 12px;
        }

        @page {
            margin: 10mm 0mm 10mm 0mm; /* margens: cima, direita, baixo, esquerda */
        }

    </style>
</head>
<body>

<div class="max-w-4xl mx-auto bg-white p-8">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold">Presupuesto</h1>
            <p class="text-gray-600">Presupuesto #{{ $budget->code }}</p>
        </div>
        <div class="text-right">

            @if($setting->logo != '')
                @php
                    $imagePath = storage_path('app/public/'.$setting->logo);
                    $imageBase64 = 'data:image/png;base64,'.base64_encode(file_get_contents($imagePath));
                @endphp


                <img class="w-[300px] h-auto ms-auto" src="{{ $imageBase64 }}" alt="{{ $setting->title }}">
            @else
                <h2 class="text-xl font-semibold">{{ $setting->title }}</h2>
            @endif
            <p>{{ $setting->address }}</p>
            <p>{{ $setting->city }} - {{ $setting->postal_code }}</p>
            <p>{{ $setting->email }}</p>
            <p>{{ $setting->whatsapp }}</p>
        </div>
    </div>

    <fieldset class="mt-8 grid grid-cols-2 gap-8 border py-2 px-3">

        <legend class="fieldset-legend">Cliente</legend>
        <div>
            <p><b>Nombre:</b> {{ $budget->customer->name }}</p>
            <p><b>Dni/Nif:</b> {{ $budget->customer->document }}</p>
            <p><b>Direccíon:</b> {{ $budget->customer->address }}</p>
            <p><b>Correo eletronico:</b> {{ $budget->customer->email }}</p>
        </div>
        <div class="text-right">
            <h3 class="font-semibold">Fecha Presupuesto</h3>
            <p>{{ \Carbon\Carbon::parse($budget->created_at)->format('d/m/Y') }}</p>
            <h3 class="font-semibold mt-4">Fecha de Validad</h3>
            <p>30 días</p>
        </div>
    </fieldset>

    <table class="min-w-full mt-8 border-collapse">
        <thead>
        <tr class="bg-gray-200">
            @if($budget->show_service == true)
            <th class="py-2 px-4 text-left">Servício</th>
            @endif
                @if($budget->show_description == true)
            <th class="py-2 px-4 text-left max-w-[420px] ">Descripción</th>
                @endif
                @if($budget->show_qtd == true)
                    <th class="py-2 px-4 text-left">Cant</th>
                @endif
                @if($budget->show_price == true)
                    <th class="py-2 px-4 text-left">Unit Precio</th>
                @endif
            @if($budget->show_tax == true)
                <th class="py-2 px-4 text-left">Iva</th>
            @endif
            @if($budget->show_total_tax == true)
                <th class="py-2 px-4 text-left">Total Iva</th>
            @endif
            @if($budget->show_total == true)
                <th class="py-2 px-4 text-left">Total S/Iva</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @foreach($budget->items as $item)
            <tr class="border-t">
                @if($budget->show_service == true)
                <td class="py-2 px-4 {{ ($item->total == 0)? 'bg-gray-50' : '' }}">
                    {!! ($item->total == 0)? '<b>'.$item->product->name.'</b>':$item->product->name !!}
                </td>
                @endif
                    @if($budget->show_description == true)
                <td class="py-2 px-4 max-w-[420px]  {{ ($item->total == 0)? 'bg-gray-50' : '' }}">
                    {!! $item->description !!}
                </td>
                    @endif
                    @if($budget->show_qtd == true)
                        <td class="py-2 px-4 {{ ($item->total == 0)? 'bg-gray-50' : '' }}">
                            {{ ($item->total == 0)? '': $item->quantity }}
                        </td>
                    @endif
                    @if($budget->show_price == true)
                        <td class="py-2 px-4 {{ ($item->total == 0)? 'bg-gray-50' : '' }}">
                            {{ ($item->total == 0)? '': $item->price }}
                        </td>
                    @endif
                @if($budget->show_tax == true)
                    <td class="py-2 px-4 {{ ($item->total == 0)? 'bg-gray-50' : '' }}">
                        {{ ($item->total == 0)? '': $item->tax }}
                    </td>
                @endif
                @if($budget->show_total_tax == true)
                    <td class="py-2 px-4 text-end {{ ($item->total == 0)? 'bg-gray-50' : '' }}">
                        {{ ($item->total == 0)? '': '€ '.$item->total_tax }}
                    </td>
                @endif
                @if($budget->show_total == true)
                    <td class="py-2 px-4 text-end {{ ($item->total == 0)? 'bg-gray-50' : '' }}">
                        {{ ($item->total == 0)? '': '€ '.$item->total }}
                    </td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>



    <!-- Aqui começam as 3 caixas -->
    <div class="mt-8 grid grid-cols-1 sm:grid-cols-3 gap-8">
        <div class="bg-gray-200 p-4">
            <h3 class="font-semibold ">Observacion</h3>
            <p class="text-gray-700">{!!$budget->description !!}</p>
        </div>
        <div class="bg-gray-200 p-4">
            @if($budget->show_tax == true)
                <h3 class="font-semibold text-lg ">Iva</h3>
                <p class="text-gray-700 text-end">{{ $budget->tax }}</p>
            @endif
        </div>
        <div class="bg-gray-200 p-4">
            <h3 class="font-semibold text-lg">Total</h3>
            @if($budget->show_total == true)
                <h3 class="font-semibold text-gray-700 text-end">€ {{ $budget->total }}</h3>
            @endif
            @if($budget->show_total_tax == true)
                <h3 class="font-semibold text-gray-700 text-end">€ {{ $budget->total_tax }}</h3>
            @endif
        </div>
    </div>


</div>


</body>
</html>
