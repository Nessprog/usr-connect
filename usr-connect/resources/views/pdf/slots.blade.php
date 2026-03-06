<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: sans-serif;
        }

        .header {
            text-align: center;
            color: #5D2E8E;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #5D2E8E;
            color: white;
            text-transform: uppercase;
        }

        .footer {
            margin-top: 20px;
            font-size: 10px;
            text-align: center;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>SOLIDA'FOOT 2026</h1>
        <h2>Planning : Pôle {{ $category }}</h2>
    </div>

    <table>
        <thead>
            <tr>
                <th>Mission</th>
                <th>Début</th>
                <th>Fin</th>
                <th>Inscrits/Capacité</th>
            </tr>
        </thead>
        <tbody>
            @foreach($slots as $slot)
            <tr>
                <td><strong>{{ $slot->title }}</strong></td>
                <td>{{ \Carbon\Carbon::parse($slot->start_time)->format('d/m H:i') }}</td>
                <td>{{ \Carbon\Carbon::parse($slot->end_time)->format('H:i') }}</td>
                <td>{{ $slot->users_count }} / {{ $slot->max_volunteers }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">Document généré le {{ $date }} - USR Connect</div>
</body>

</html>