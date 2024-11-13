<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center">
            {{ __('Estado de Resultados de ') . $empresa->nombre }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-xl font-bold mb-4 text-center">Estado de Resultados</h3>

                <table class="min-w-full bg-white border w-full">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border text-left">Cuenta</th>
                            <th class="py-2 px-4 border text-right">Monto (Bs)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Ingresos -->
                        <tr><td colspan="2" class="font-bold py-2 px-4 border">Ingresos</td></tr>
                        <tr><td class="py-2 px-4 border">Ventas</td><td class="py-2 px-4 border text-right">{{ number_format($totales['Ventas'], 2) }}</td></tr>
                        <tr><td class="py-2 px-4 border">Ingresos Financieros</td><td class="py-2 px-4 border text-right">{{ number_format($totales['Ingresos Financieros'], 2) }}</td></tr>
                        <tr><td class="py-2 px-4 border">Ingresos Extraordinarios</td><td class="py-2 px-4 border text-right">{{ number_format($totales['Ingresos Extraordinarios'], 2) }}</td></tr>

                        <!-- Costos de Ventas -->
                        <tr><td colspan="2" class="font-bold py-2 px-4 border">Costos</td></tr>
                        <tr><td class="py-2 px-4 border">Costo de Ventas</td><td class="py-2 px-4 border text-right">{{ number_format($totales['Costo de Ventas'], 2) }}</td></tr>

                        <!-- Gastos Operativos -->
                        <tr><td colspan="2" class="font-bold py-2 px-4 border">Gastos Operativos</td></tr>
                        <tr><td class="py-2 px-4 border">Gastos de Administración</td><td class="py-2 px-4 border text-right">{{ number_format($totales['Gastos de Administración'], 2) }}</td></tr>
                        <tr><td class="py-2 px-4 border">Gastos de Ventas</td><td class="py-2 px-4 border text-right">{{ number_format($totales['Gastos de Ventas'], 2) }}</td></tr>
                        <tr><td class="py-2 px-4 border">Gastos Financieros</td><td class="py-2 px-4 border text-right">{{ number_format($totales['Gastos Financieros'], 2) }}</td></tr>
                        <tr><td class="py-2 px-4 border">Gastos Extraordinarios</td><td class="py-2 px-4 border text-right">{{ number_format($totales['Gastos Extraordinarios'], 2) }}</td></tr>

                        <!-- Impuestos -->
                        <tr><td colspan="2" class="font-bold py-2 px-4 border">Impuestos</td></tr>
                        <tr><td class="py-2 px-4 border">Impuesto sobre la Renta</td><td class="py-2 px-4 border text-right">{{ number_format($totales['Impuesto sobre la Renta'], 2) }}</td></tr>

                        <!-- Utilidad Neta -->
                        <tr><td colspan="2" class="font-bold py-2 px-4 border">Utilidad Neta</td></tr>
                        <tr><td class="py-2 px-4 border">Total Utilidad Neta</td><td class="py-2 px-4 border text-right font-bold">{{ number_format($utilidadNeta, 2) }}</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
