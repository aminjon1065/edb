<!DOCTYPE html>
<html lang="en">
<head>
    <title>Report PDF</title>
</head>
<body>
<h2>Report</h2>
<table>
    <thead>
    <tr>
        <th>Type TJ</th>
        <th>Type RU</th>
        <th>Count</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($grouped as $item)
        <tr>
            <td>{{ $item['type_tj'] }}</td>
            <td>{{ $item['type_ru'] }}</td>
            <td>{{ $item['count'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
