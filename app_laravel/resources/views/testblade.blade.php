<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>Пример blade шаблона</title>
    </head>
    <body>
        <p>a: {{ $a }}</p>
        <p>b: {{ $b }}</p>
        <p>b: {!! $b !!}</p>
        <p>c: {{ $c }}</p>

        {{ $a.$c }}
    </body>
</html>
