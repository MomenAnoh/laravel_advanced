<!DOCTYPE html>
<html>
<head>
    <title>Redirecting to JazzCash...</title>
</head>
<body onload="document.getElementById('jazzcashForm').submit()">

<form id="jazzcashForm" method="POST" action="{{ $url }}">

    @foreach($data as $key => $value)
        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
    @endforeach

</form>

<p>Redirecting to JazzCash Payment Page...</p>

</body>
</html>