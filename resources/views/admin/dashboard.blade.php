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
                        <div class="hero-section text-center py-5 mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 10px; width: 100%;">
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
                                <canvas id="dailyChart" width="200" height="200"></canvas>
                                <div class="mt-3 text-center">
                                    <p class="mb-1">Estudiantes: <span id="daily-estudiante">{{ $stats['daily']['estudiante'] }}</span></p>
                                    <p class="mb-1">Empleados: <span id="daily-empleado">{{ $stats['daily']['empleado'] }}</span></p>
                                    <p class="font-weight-bold">Total: <span id="daily-total">{{ $stats['daily']['total'] }}</span></p>
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
                                <canvas id="weeklyChart" width="200" height="200"></canvas>
                                <div class="mt-3 text-center">
                                    <p class="mb-1">Estudiantes: <span id="weekly-estudiante">{{ $stats['weekly']['estudiante'] }}</span></p>
                                    <p class="mb-1">Empleados: <span id="weekly-empleado">{{ $stats['weekly']['empleado'] }}</span></p>
                                    <p class="font-weight-bold">Total: <span id="weekly-total">{{ $stats['weekly']['total'] }}</span></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mensual -->
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0"><i class="bi bi-calendar-month"></i> Entradas de Este Mes</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="monthlyChart" width="200" height="200"></canvas>
                                <div class="mt-3 text-center">
                                    <p class="mb-1">Estudiantes: <span id="monthly-estudiante">{{ $stats['monthly']['estudiante'] }}</span></p>
                                    <p class="mb-1">Empleados: <span id="monthly-empleado">{{ $stats['monthly']['empleado'] }}</span></p>
                                    <p class="font-weight-bold">Total: <span id="monthly-total">{{ $stats['monthly']['total'] }}</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let dailyChart, weeklyChart, monthlyChart;

        function createCharts(data) {
            const dailyData = {
                labels: ['Estudiantes', 'Empleados'],
                datasets: [{
                    data: [data.daily.estudiante, data.daily.empleado],
                    backgroundColor: ['#007bff', '#28a745'],
                    hoverBackgroundColor: ['#0056b3', '#1e7e34']
                }]
            };

            const weeklyData = {
                labels: ['Estudiantes', 'Empleados'],
                datasets: [{
                    data: [data.weekly.estudiante, data.weekly.empleado],
                    backgroundColor: ['#28a745', '#ffc107'],
                    hoverBackgroundColor: ['#1e7e34', '#e0a800']
                }]
            };

            const monthlyData = {
                labels: ['Estudiantes', 'Empleados'],
                datasets: [{
                    data: [data.monthly.estudiante, data.monthly.empleado],
                    backgroundColor: ['#17a2b8', '#dc3545'],
                    hoverBackgroundColor: ['#138496', '#c82333']
                }]
            };

            if (dailyChart) dailyChart.destroy();
            if (weeklyChart) weeklyChart.destroy();
            if (monthlyChart) monthlyChart.destroy();

            dailyChart = new Chart(document.getElementById('dailyChart'), {
                type: 'pie',
                data: dailyData,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        }
                    }
                }
            });

            weeklyChart = new Chart(document.getElementById('weeklyChart'), {
                type: 'pie',
                data: weeklyData,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        }
                    }
                }
            });

            monthlyChart = new Chart(document.getElementById('monthlyChart'), {
                type: 'pie',
                data: monthlyData,
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        }
                    }
                }
            });
        }

        function updateStats() {
            fetch('{{ route("admin.dashboard.stats") }}')
                .then(response => response.json())
                .then(data => {
                    // Update text
                    document.querySelector('#daily-estudiante').textContent = data.daily.estudiante;
                    document.querySelector('#daily-empleado').textContent = data.daily.empleado;
                    document.querySelector('#daily-total').textContent = data.daily.total;

                    document.querySelector('#weekly-estudiante').textContent = data.weekly.estudiante;
                    document.querySelector('#weekly-empleado').textContent = data.weekly.empleado;
                    document.querySelector('#weekly-total').textContent = data.weekly.total;

                    document.querySelector('#monthly-estudiante').textContent = data.monthly.estudiante;
                    document.querySelector('#monthly-empleado').textContent = data.monthly.empleado;
                    document.querySelector('#monthly-total').textContent = data.monthly.total;

                    // Update charts
                    createCharts(data);
                })
                .catch(error => console.error('Error fetching stats:', error));
        }

        // Initial load
        updateStats();

        // Update every 60 seconds
        setInterval(updateStats, 60000);
    </script>
@endsection
