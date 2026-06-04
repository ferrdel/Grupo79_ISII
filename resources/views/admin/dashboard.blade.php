@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 py-3">
    
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4 pb-2 border-bottom">
        <div>
            <h1 class="h2 text-gray-800">Panel de Control (Dashboard)</h1>
            <p class="text-muted mb-0">Análisis de rendimiento y demanda estacional.</p>
        </div>
        
        <div class="btn-toolbar mb-2 mb-md-0">
            <form action="{{ route('admin.dashboard') }}" method="GET" class="d-flex gap-2 bg-white p-2 rounded shadow-sm">
                <label for="anio" class="col-form-label px-2 small text-muted">Año Analizado:</label>
                <select name="anio" id="anio" class="form-select form-select-sm border-0 bg-light" style="width: 120px;">
                    <option value="2026" {{ $anio == 2026 ? 'selected' : '' }}>2026</option>
                    <option value="2025" {{ $anio == 2025 ? 'selected' : '' }}>2025</option>
                </select>
                <button type="submit" class="btn btn-sm btn-primary px-3">
                    Filtrar
                </button>
            </form>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 border-start border-primary border-4 shadow-sm bg-white border-0">
                <div class="card-body py-3">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1" style="font-size: 0.75rem; font-weight: 700;">Total Reservas del Año</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalReservas }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card h-100 border-start border-warning border-4 shadow-sm bg-white border-0">
                <div class="card-body py-3">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1" style="font-size: 0.75rem; font-weight: 700;">Meses en Alerta Crítica</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ count($mesesBajaDemanda) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="card shadow-sm bg-white h-100 border-0">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h6 class="m-0 font-weight-bold text-primary" style="font-weight: 700;">Historial Estacional de Reservas por Mes</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area" style="position: relative; height: 320px; width: 100%;">
                        <canvas id="canvasDemandaEstacional"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card shadow-sm bg-white h-100 border-0">
                <div class="card-header bg-white py-3 border-bottom-0">
                    <h6 class="m-0 font-weight-bold text-danger" style="font-weight: 700;">Alertas de Baja Ocupación</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">El sistema detectó baja demanda en los siguientes períodos. Se recomienda activar promociones específicas:</p>

                    @if(count($mesesBajaDemanda) > 0)
                        <div class="d-flex flex-column gap-2">
                            @php
                                $mesesNombres = [
                                    1=>'Enero', 2=>'Febrero', 3=>'Marzo', 4=>'Abril', 
                                    5=>'Mayo', 6=>'Junio', 7=>'Julio', 8=>'Agosto', 
                                    9=>'Septiembre', 10=>'Octubre', 11=>'Noviembre', 12=>'Diciembre'
                                ];
                            @endphp
                            
                            @foreach($mesesBajaDemanda as $numMes)
                                <div class="p-3 bg-light rounded border d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0 text-dark" style="font-weight: 700;">{{ $mesesNombres[$numMes] }}</h6>
                                        <small class="text-danger">Reservas por debajo del umbral mínimo</small>
                                    </div>
                                    <a href="{{ route('promociones.create', ['mes' => $numMes, 'anio' => $anio]) }}" class="btn btn-sm btn-outline-danger">
                                        Lanzar Promo
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="alert alert-success d-inline-block small mb-0" role="alert">
                                ¡Excelente! Todos los períodos mantienen una demanda de alquiler saludable.
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Enlazamos el script al canvas mediante su ID único
        const ctx = document.getElementById('canvasDemandaEstacional').getContext('2d');
        
        // Conversión e inyección segura de las variables procesadas en Laravel a JSON legible por JS
        const nombresMeses = {!! json_encode($nombresMeses) !!}; 
        const datosReservas = {!! json_encode($datosGrafico) !!}; 

        // 2. Renderizado de Chart.js
        // REVISA ESTA SECCIÓN: Asegúrate de que apunte a las constantes que definiste arriba
        const miGrafico = new Chart(ctx, {
            type: 'line', // Gráfico de línea
            data: {
                labels: nombresMeses, // Usa el arreglo de nombres (Enero, Febrero...)
                datasets: [{
                    label: 'Cantidad de Reservas',
                    data: datosReservas, // ¡REVISA ESTA LÍNEA! Debe decir datosReservas
                    backgroundColor: 'rgba(54, 162, 235, 0.1)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    tension: 0.3, // Le da la curvatura suave a la línea del historial
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false // Mantiene tu UI limpia sin leyendas flotantes
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1 // Fuerza a que el eje Y muestre números enteros (1, 2, 3 reservas)
                        }
                    }
                }
            }
        });
    });
</script>

@endsection {{-- AQUÍ TERMINA DEFINITIVAMENTE EL BLOQUE HTML DE LA VISTA --}}


