<!DOCTYPE html>
<html>
<head>
    <title>Planes de Cuentas</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background-color: #f8f8f8;
            color: #333;
        }

        h1 {
            text-align: center;
            color: #2c3e50;
            font-weight: 300;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd; /* Asegura bordes visibles */
        }

        th {
            background-color: #ecf0f1;
            color: #34495e;
            font-weight: 600;
        }

        td {
            color: #2c3e50;
        }

        .rounded {
            border-radius: 10px;
        }

        .details-table {
            width: 95%;
            margin: 10px auto;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #ddd; /* Bordes visibles */
        }

        .details-table th, .details-table td {
            border: 1px solid #ddd; /* Asegura bordes en la tabla interna */
        }

        .details-table th {
            background-color: #bdc3c7;
            color: #2c3e50;
        }

        .section-title {
            font-size: 14px;
            font-weight: 600;
            color: #2c3e50;
            padding: 8px;
            background-color: #ecf0f1;
            border-bottom: 1px solid #ccc;
            margin-top: 12px;
        }

        .font-bold {
            font-weight: bold;
            background-color: #dfe6e9;
            padding: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Planes de Cuentas</h1>
    <table class="rounded">
        <thead>
            <tr>
                <th>Empresa</th>
                <th>Fecha</th>
                <th>Detalles (Cuentas Asociadas)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($planesDeCuentas as $plan)
                <tr>
                    <td>{{ $plan->empresa->nombre }}</td>
                    <td>{{ $plan->fecha }}</td>
                    <td>
                        <table class="details-table">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Cuenta</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $codigoTipoCuenta = [
                                        'activo_corriente' => 1,
                                        'activo_no_corriente' => 2,
                                        'pasivo_corriente' => 3,
                                        'pasivo_no_corriente' => 4,
                                        'patrimonio' => 5
                                    ];
                                    $contadores = [
                                        'activo_corriente' => 1,
                                        'activo_no_corriente' => 1,
                                        'pasivo_corriente' => 1,
                                        'pasivo_no_corriente' => 1,
                                        'patrimonio' => 1
                                    ];
                                    $titulosTipos = [
                                        'activo_corriente' => '1 Activos Corrientes',
                                        'activo_no_corriente' => '2 Activos No Corrientes',
                                        'pasivo_corriente' => '3 Pasivos Corrientes',
                                        'pasivo_no_corriente' => '4 Pasivos No Corrientes',
                                        'patrimonio' => '5 Patrimonio'
                                    ];
                                    $detallesOrdenados = $plan->detalles->sortBy(function($detalle) use ($codigoTipoCuenta) {
                                        return $codigoTipoCuenta[$detalle->cuenta->tipo] ?? 999;
                                    });
                                    $tipoAnterior = null;
                                @endphp

                                @foreach ($detallesOrdenados as $detalle)
                                    @php
                                        $tipoCuenta = $detalle->cuenta->tipo;
                                        if ($tipoCuenta !== $tipoAnterior) {
                                            echo '<tr><td colspan="2" class="font-bold">' . $titulosTipos[$tipoCuenta] . '</td></tr>';
                                            $tipoAnterior = $tipoCuenta;
                                        }
                                        $codigo = $codigoTipoCuenta[$tipoCuenta] . '.' . $contadores[$tipoCuenta];
                                        $contadores[$tipoCuenta]++;
                                    @endphp
                                    <tr>
                                        <td>{{ $codigo }}</td>
                                        <td>{{ $detalle->cuenta->nombre }} ({{ $detalle->cuenta->tipo }})</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
