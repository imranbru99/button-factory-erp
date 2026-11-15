<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><style>body{font-family:DejaVu Sans,sans-serif;font-size:12px} table{width:100%;border-collapse:collapse} td,th{border-bottom:1px solid #ddd;padding:6px;text-align:left}</style></head>
<body>
<h1>Packing list {{ $document->number }}</h1>
<p>{{ $document->salesOrder->customer->name }} · {{ $printed }}</p>
<table>
    <tr><th>Button</th><th>Qty</th></tr>
    @foreach ($document->lines as $line)
        <tr><td>{{ $line->product->name }}</td><td>{{ $line->qty }}</td></tr>
    @endforeach
</table>
</body>
</html>
