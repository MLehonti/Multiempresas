<!DOCTYPE html>
<html>
<head>
    <title>Lista de Empresas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            color: #333;
        }

        h1 {
            text-align: center;
            color: #4a4a4a;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #3490dc; /* Azul bonito */
            color: white;
        }

        tr:nth-child(even) td {
            background-color: #f2f8ff; /* Fondo claro para filas pares */
        }

        tr:nth-child(odd) td {
            background-color: #e8f3ff; /* Fondo un poco más oscuro para filas impares */
        }

        td {
            border-bottom: 1px solid #ddd;
        }

        tr:last-child td {
            border-bottom: none; /* Quitar borde en la última fila */
        }

        tr:hover td {
            background-color: #d0e7ff; /* Efecto hover */
        }

        td, th {
            border: 1px solid #dddddd; /* Borde de cada celda */
            border-radius: 8px;
        }

    </style>
</head>
<body>
    <h1>Lista de Empresas</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Rubro</th>
                <th>Propietario</th>
            </tr>
        </thead>
        <tbody>
            @foreach($empresas as $empresa)
                <tr>
                    <td>{{ $empresa->id }}</td>
                    <td>{{ $empresa->nombre }}</td>
                    <td>{{ $empresa->rubro->nombre }}</td>
                    <td>{{ $empresa->user->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
