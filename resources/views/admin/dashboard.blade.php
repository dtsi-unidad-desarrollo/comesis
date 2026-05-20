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

                <!-- Estadísticas -->
                <div class="row mt-4">
                    <!-- Diario -->
                    <div class="col-6 mb-4">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="bi bi-calendar-day"></i> Bandejas entregadas Hoy {{ Carbon\Carbon::now()->format('d-m-Y') }}</h5>
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
                    <div class="col-12 mb-4">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class="bi bi-calendar-week"></i> Bandejas entregadas de Esta Semana</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="weeklyChart" width="400" height="200"></canvas>
                                <div class="mt-3 text-center">
                                    <p class="mb-1">Total de bandejas entregadas a estudiantes: <span
                                            id="weekly-estudiante">{{ $stats['semanal']['total']['ESTUDIANTE'] }}</span></p>
                                    <p class="mb-1">Total de bandejas entregadas a empleados: <span
                                            id="weekly-empleado">{{ $stats['semanal']['total']['EMPLEADO'] }}</span></p>
                                    <p class="font-weight-bold">Total bruto: <span
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
        const ctxToday = document.getElementById('todayChart');
        const ctxWeek = document.getElementById('weeklyChart');
        const ctxMonth = document.getElementById('monthlyChart');

        const todayChart = new Chart(ctxToday, {
            type: 'pie',
            data: {
                labels: ['ESTUDIANTE', 'EMPLEADO', 'BANDEJAS NO ENTREGADAS'],
                datasets: [{
                    label: '',
                    data: [
                        {{ $stats['hoy']['ESTUDIANTE'] }},
                        {{ $stats['hoy']['EMPLEADO'] }},
                        {{ $stats['hoy']['no_entregadas'] }}
                    ],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(128, 128, 128, 0.7)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(128, 128, 128, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        max: {{ $stats['hoy']['disponibles'] }},
                    }
                }
            }
        });

        const weeklyChart = new Chart(ctxWeek, {
            type: 'bar',
            data: {
                labels: [
                  
                    "{{  $stats['semanal']['lunes']['fecha'] }} - LUNES",
                    "{{  $stats['semanal']['martes']['fecha'] }} - MARTES",
                    "{{  $stats['semanal']['miercoles']['fecha']}} - MIERCOLES",
                    "{{  $stats['semanal']['jueves']['fecha'] }} - JUEVES",
                    "{{  $stats['semanal']['viernes']['fecha'] }} - VIERNES",
                    "{{  $stats['semanal']['sabado']['fecha'] }} - SABADO",
                ],
                datasets: [{
                        label: 'Estudiantes',
                        data: [
                            {{ $stats['semanal']['lunes']['ESTUDIANTE'] }},
                            {{ $stats['semanal']['martes']['ESTUDIANTE'] }},
                            {{ $stats['semanal']['miercoles']['ESTUDIANTE'] }},
                            {{ $stats['semanal']['jueves']['ESTUDIANTE'] }},
                            {{ $stats['semanal']['viernes']['ESTUDIANTE'] }},
                            {{ $stats['semanal']['sabado']['ESTUDIANTE'] }},
                        ],
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.7)',
                        ],
                        borderColor: [
                            'rgba(54, 162, 235, 1)',
                           
                        ],
                        borderWidth: 1
                    },
                    {
                        label: 'Empleados',
                        data: [
                            {{ $stats['semanal']['lunes']['EMPLEADO'] }},
                            {{ $stats['semanal']['martes']['EMPLEADO'] }},
                            {{ $stats['semanal']['miercoles']['EMPLEADO'] }},
                            {{ $stats['semanal']['jueves']['EMPLEADO'] }},
                            {{ $stats['semanal']['viernes']['EMPLEADO'] }},
                            {{ $stats['semanal']['sabado']['EMPLEADO'] }},
                        ],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.7)',
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                        ],
                        borderWidth: 1
                    }
                ]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 2000,
                    }
                }
            }
        });
    </script>
@endsection
