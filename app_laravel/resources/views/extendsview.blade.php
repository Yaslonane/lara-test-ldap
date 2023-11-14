<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>@yield('title', 'Заголовок страницы')</title>
    </head>
    <body>
        <div>
            Шапка сайта
        </div>
        <div>@importantmessage(Very important message)</div>
    </body>
</html>
