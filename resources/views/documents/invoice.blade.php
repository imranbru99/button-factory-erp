<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><style>body{font-family:DejaVu Sans,sans-serif;font-size:12px;color:#1c1712} h1{font-size:20px} table{width:100%;border-collapse:collapse} td,th{border-bottom:1px solid #ddd;padding:6px;text-align:left}</style></head>
<body>
<h1>{{ config('factory.name') }}</h1>
<p>চালান / Invoice {{ $document->number }} · {{ $printed }}</p>
<p>{{ $document->customer->name }} · {{ $document->customer->city }}</p>
<table>
    <tr><th>Description</th><th>Qty</th><th>Price</th></tr>
    @foreach ($document->lines as $line)
        <tr><td>{{ $line->description }}</td><td>{{ $line->qty }}</td><td>{{ $line->unit_price }}</td></tr>
    @endforeach
</table>
<p>Total {{ $document->currency }} {{ number_format((float) $document->total, 2) }}</p>
</body>
</html>
