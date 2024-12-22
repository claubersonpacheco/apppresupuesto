<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f4f4f4; }
    </style>
</head>
<body>
<h2>Itens do Orçamento</h2>
<table>
    <thead>
    <tr>
        @if ($visibleColumns['product.name'] ?? false)
            <th>Serviço</th>
        @endif
        @if ($visibleColumns['description'] ?? false)
            <th>Descrição</th>
        @endif
        @if ($visibleColumns['tax'] ?? false)
            <th>IVA %</th>
        @endif
        @if ($visibleColumns['total'] ?? false)
            <th>Valor s/Iva</th>
        @endif
        @if ($visibleColumns['total_tax'] ?? false)
            <th>Valor c/Iva</th>
        @endif
    </tr>
    </thead>
    <tbody>
    @foreach($items as $item)
        <tr>
            @if ($visibleColumns['product.name'] ?? false)
                <td>{{ $item->product->name }}</td>
            @endif
            @if ($visibleColumns['description'] ?? false)
                <td>{!! $item->description !!}</td>
            @endif
            @if ($visibleColumns['tax'] ?? false)
                <td>{{ $item->tax }}</td>
            @endif
            @if ($visibleColumns['total'] ?? false)
                <td>{{ number_format($item->total, 2) }}</td>
            @endif
            @if ($visibleColumns['total_tax'] ?? false)
                <td>{{ number_format($item->total_tax, 2) }}</td>
            @endif
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>

