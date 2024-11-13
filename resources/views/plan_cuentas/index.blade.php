<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mis Planes de Cuentas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <h3 class="text-xl font-bold mb-4">Vista del Plan de Cuentas</h3>

                <!-- Aquí va la tabla -->
                <table class="min-w-full bg-white border w-full">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border">Empresa</th>
                            <th class="py-2 px-4 border">Fecha</th>
                            <th class="py-2 px-4 border">Detalles (Cuentas Asociadas)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($planesDeCuentas as $plan)
                            <tr>
                                <td class="py-2 px-4 border">{{ $plan->empresa->nombre }}</td>
                                <td class="py-2 px-4 border">{{ $plan->fecha }}</td>
                                <td class="py-2 px-4 border">
                                    <table class="min-w-full bg-white border w-full">
                                        <thead>
                                            <tr>
                                                <th class="py-2 px-4 border">Código</th>
                                                <th class="py-2 px-4 border">Cuenta</th>
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
                                                        echo '<tr><td colspan="2" class="border px-4 py-2 font-bold">' . $titulosTipos[$tipoCuenta] . '</td></tr>';
                                                        $tipoAnterior = $tipoCuenta;
                                                    }
                                                    $codigo = $codigoTipoCuenta[$tipoCuenta] . '.' . $contadores[$tipoCuenta];
                                                    $contadores[$tipoCuenta]++;
                                                @endphp
                                                <tr>
                                                    <td class="py-2 px-4 border">{{ $codigo }}</td>
                                                    <td class="py-2 px-4 border">{{ $detalle->cuenta->nombre }} ({{ $detalle->cuenta->tipo }})</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Contenedor para los botones alineado a la derecha -->
                <div class="flex justify-end mt-4">
                    <a href="{{ route('plan-cuentas.export.excel') }}" style="background-color: #28a745; color: white; padding: 0.5rem 1rem; border-radius: 0.5rem; text-decoration: none; margin-right: 10px;">
                        Descargar Excel
                    </a>
                    <a href="{{ route('plan-cuentas.export.pdf') }}" style="background-color: #3490dc; color: white; padding: 0.5rem 1rem; border-radius: 0.5rem; text-decoration: none;">
                        Descargar PDF
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
