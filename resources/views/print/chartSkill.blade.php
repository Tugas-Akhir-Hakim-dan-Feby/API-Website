<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Minat Welder</title>
    <style>
        body {
            padding-inline: 3cm;
            padding-block: 1cm;
        }

        table {
            width: 100%;
            border-collapse: collapse
        }

        table,
        th,
        td {
            text-align: left;
            border: 1px solid black;
            padding: 10px;
        }

        .text-center {
            text-align: center
        }
    </style>
</head>

<body>
    <h3 class="text-center">Rekapitulasi Minat Welder</h3>
    <p class="text-center">Rekapitulasi minat welder diambil dari data pengguna Member Individu.</p>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Keahlian</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($skills as $skill)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $skill->skill_name }}</td>
                    <td>{{ $skill->skillHasMember->count() }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
