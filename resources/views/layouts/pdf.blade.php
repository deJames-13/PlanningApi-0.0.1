<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="content">
        @yield('content')
    </div>
</body>
</html>