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
        <h2>Planning : {{ $slot->title }}</h2>
    </div>

    <p>Date : {{ $slot->start_time->format('d/m/Y') }} {{ $slot->start_time->format('H:i') }} - {{ $slot->end_time->format('H:i') }} Nombre d'inscrits: {{ $slot->users->count() }}</p>

    <table border="1" width="100%" style="border-collapse: collapse;">
        <thead>
            <tr>
                <th>Nom du bénévole</th>
                <th>Téléphone</th>
            </tr>
        </thead>
        <tbody>
            @foreach($slot->users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->phone ?? 'Non renseigné' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>