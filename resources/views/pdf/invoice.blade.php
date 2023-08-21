<!doctype html>
<html lang={{$lang}}>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Document</title>
</head>
<body>
<h2>Report</h2>
<table>
    <thead>
    <tr>
        @if($lang != 'ru')
            <th>Номгу</th>
            <th>миқдор</th>
        @else
            <th>Нвзвание</th>
            <th>кол-во</th>
        @endif
    </tr>
    </thead>
    <tbody>
    @if($lang != 'ru')
        @foreach ($grouped as $item)
            <tr>
                <td>{{ $item['type_tj'] }}</td>
                <td>{{ $item['count'] }}</td>
            </tr>
        @endforeach
    @else
        @foreach ($grouped as $item)
            <tr>
                <td>{{ $item['type_ru'] }}</td>
                <td>{{ $item['count'] }}</td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>
</body>
</html>
