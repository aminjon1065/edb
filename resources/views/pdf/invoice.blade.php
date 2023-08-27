<!doctype html>
<html lang={{$lang}}>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @font-face {
            font-family: 'DejaVu Sans';
            src: local('DejaVu Sans'), local('DejaVuSans'), url('dejavusans.woff2') format('woff2'), url('dejavusans.woff') format('woff'), url('dejavusans.ttf') format('truetype');
            font-weight: 400;
            font-style: normal;
        }

        body {
            font-family: "dejavu sans", serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
    <title>{{$lang==="ru" ? "Отчёт" : "Ҳисобот"}}</title>
</head>
<body>
<div class="container mx-auto p-5">
    <h2>{{$lang==="ru" ? "от" : "аз"}} {{date('d-m-Y', strtotime($start))}} {{$lang==="ru" ? "до" : "то"}}  {{date('d-m-Y', strtotime($end))}} / {{$filedToBlade}}</h2>
    <table>
        <thead>
        <tr>
            <th>{{$lang==="ru" ? "Тип" : "Намуд"}}</th>
            <th>{{$lang==="ru" ? "Количество" : "Миқдор"}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($grouped as $code => $group)
            <tr>
                <td>{{ $lang === 'ru' ? $group['type_ru'] : $group['type_tj'] }}</td>
                <td>{{ $group['count'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>
</body>
</html>
