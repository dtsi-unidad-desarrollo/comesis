@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    @if (session('mensaje'))
        @include('partials.alert')
    @endif
    <div id="alert"></div>

    <div class="container">
        <section class="section register d-flex flex-column align-items-center justify-content-center ">
            <div class="container">
                <div class="row justify-content-center">
                    <div class=" col-sm-12 d-flex flex-column align-items-center justify-content-center">
                        <div class="hero-section text-center py-5 mb-4"
                            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 10px; width: 100%;">
                            <p class="lead">Monitorea las entradas en tiempo real</p>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas -->
                <div class="row mt-4">
                    <!-- Diario -->
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="bi bi-calendar-day"></i> Entradas de Hoy</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="todayChart" width="400" height="200"></canvas>
                                <div class="mt-3 text-center">
                                    <p class="mb-1">Estudiantes: <span
                                            id="daily-estudiante">{{ $stats['hoy']['ESTUDIANTE'] }}</span></p>
                                    <p class="mb-1">Empleados: <span
                                            id="daily-empleado">{{ $stats['hoy']['EMPLEADO'] }}</span></p>
                                    <p class="font-weight-bold">Total: <span
                                            id="daily-total">{{ $stats['hoy']['total'] }}</span></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Semanal -->
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class="bi bi-calendar-week"></i> Entradas de Esta Semana</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="weeklyChart" width="400" height="200"></canvas>
                                <div class="mt-3 text-center">
                                    <p class="mb-1">Estudiantes: <span
                                            id="weekly-estudiante">{{ $stats['semanal']['total']['ESTUDIANTE'] }}</span></p>
                                    <p class="mb-1">Empleados: <span
                                            id="weekly-empleado">{{ $stats['semanal']['total']['EMPLEADO'] }}</span></p>
                                    <p class="font-weight-bold">Total: <span
                                            id="weekly-total">{{ $stats['semanal']['total']['TODOS'] }}</span></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mensual -->
                    {{-- <div class="col-md-4 mb-4">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0"><i class="bi bi-calendar-month"></i> Entradas de Este Mes</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="monthlyChart" width="200" height="200"></canvas>
                                <div class="mt-3 text-center">
                                    <p class="mb-1">Estudiantes: <span
                                            id="monthly-estudiante">{{ $stats['monthly']['estudiante'] }}</span></p>
                                    <p class="mb-1">Empleados: <span
                                            id="monthly-empleado">{{ $stats['monthly']['empleado'] }}</span></p>
                                    <p class="font-weight-bold">Total: <span
                                            id="monthly-total">{{ $stats['monthly']['total'] }}</span></p>
                                </div>
                            </div>
                        </div>
                    </div>  --}}
                </div>
            </div>
        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctxToday = document.getElementById('todayChart').getContext('2d');
        const ctxWeek = document.getElementById('weeklyChart').getContext('2d');
        const ctxMonth = document.getElementById('monthlyChart').getContext('2d');

        const todayChart = new Chart(ctxToday, {
            type: 'bar',
            data: {
                labels: ['ESTUDIANTE', 'EMPLEADO'],
                datasets: [{
                    label: 'Porcentaje de uso',
                    data: [
                        {{ $stats['hoy']['ESTUDIANTE'] }},
                        {{ $stats['hoy']['EMPLEADO'] }},
                    ],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        max: {{ $stats['hoy']['total'] > 0 ? $stats['hoy']['total'] : 100 }},
                    }
                }
            }
        });

        const weeklyChart = new Chart(ctxWeek, {
            type: 'bar',
            data: {
                labels: ['LUNES', 'MARTES', 'MIÉRCOLES', 'JUEVES', 'VIERNES', 'SÁBADO'],
                datasets: [{
                    label: 'Porcentaje de uso',
                    data: [
                        {{ $stats['semanal']['lunes']['ESTUDIANTE'] + $stats['semanal']['lunes']['EMPLEADO'] }},
                        {{ $stats['semanal']['martes']['ESTUDIANTE'] + $stats['semanal']['martes']['EMPLEADO'] }},
                        {{ $stats['semanal']['miercoles']['ESTUDIANTE'] + $stats['semanal']['miercoles']['EMPLEADO'] }},
                        {{ $stats['semanal']['jueves']['ESTUDIANTE'] + $stats['semanal']['jueves']['EMPLEADO'] }},
                        {{ $stats['semanal']['viernes']['ESTUDIANTE'] + $stats['semanal']['viernes']['EMPLEADO'] }},
                        {{ $stats['semanal']['sabado']['ESTUDIANTE'] + $stats['semanal']['sabado']['EMPLEADO'] }},
                    ],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        max: {{ $stats['semanal']['total']['TODOS'] > 0 ? $stats['semanal']['total']['TODOS'] : 100 }},
                    }
                }
            }
        });
    </script>
@endsection
