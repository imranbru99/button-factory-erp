<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><style>body{font-family:DejaVu Sans,sans-serif;font-size:12px}</style></head>
<body>
<h1>Quality certificate {{ $document->number }}</h1>
<p>Type {{ $document->type }} · Result {{ $document->result }} · {{ $printed }}</p>
<p>Sampled {{ $document->sampled_qty }} · Rejected {{ $document->rejected_qty }}</p>
<ul>
@foreach ($document->defects as $defect)
    <li>{{ $defect->defect_code }} · {{ $defect->qty }}</li>
@endforeach
</ul>
<p>{{ $document->notes }}</p>
</body>
</html>
