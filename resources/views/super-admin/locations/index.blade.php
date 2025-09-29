<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Latitude</th>
            <th>Longitude</th>
            <th>Address</th>
            <th>View Map</th>
        </tr>
    </thead>
    <tbody>
        @foreach($locations as $loc)
        <tr>
            <td>{{ $loc->id }}</td>
            <td>{{ $loc->lat }}</td>
            <td>{{ $loc->lng }}</td>
            <td>{{ $loc->address }}</td>
            <td>
                <a href="{{ route('locations.showOSM', $loc->id) }}" class="btn btn-sm btn-success" target="_blank">
                    View Map
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>