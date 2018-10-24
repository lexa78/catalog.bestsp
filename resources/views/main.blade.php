<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
</head>
<body>
    <div class="left-panel" style="border: 1px solid black; width: 10%; float: left;">
        @yield('left-side')
    </div>
    <div class="content">
        @yield('content')
    </div>
@yield('js')
</body>
</html>