@extends('layouts.app')

@section('content-app')
    <h4>📊 Rating Service Dashboard</h4>

    <form method="GET" class="mb-3">
        <input type="date" name="tanggal" value="{{ $tgl }}" class="form-control"
            style="width:300px; display:inline-block">
        <button class="btn btn-primary">Filter</button>
    </form>

    <div class="card mb-2">
        <div class="card-header py-2">Aktivitas Berdasarkan Jam</div>
        <div class="card-body p-2">
            <canvas id="chartHour"></canvas>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-2">
            <div class="card h-100">
                <div class="card-header py-2">Berdasarkan Lokasi</div>
                <div class="card-body p-2">
                    <canvas id="chartLocation"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-2">
            <div class="card h-100">
                <div class="card-header py-2">Berdasarkan Rating</div>
                <div class="card-body p-2">
                    <canvas id="chartRating"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <style>
        canvas {
            max-height: 150px !important;
        }

        #chartHour {
            max-height: 120px !important;
        }

        .card-header {
            font-size: 14px;
            font-weight: 600;
        }

        .card-body {
            height: auto;
        }
    </style>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const labelsLocation = {!! json_encode($labelsLocation) !!};
        const dataLocation = {!! json_encode($dataLocation) !!};

        // buat warna dinamis (acak lembut)
        const colors = labelsLocation.map(() =>
            `hsl(${Math.floor(Math.random() * 360)}, 70%, 60%)`
        );

        const chartLocation = new Chart(document.getElementById('chartLocation'), {
            type: 'bar',
            data: {
                labels: labelsLocation,
                datasets: [{
                    label: 'Total Response',
                    data: dataLocation,
                    backgroundColor: colors,
                    borderColor: colors.map(c => c.replace('60%',
                    '40%')), // sedikit lebih gelap untuk border
                    borderWidth: 1
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `${context.dataset.label}: ${context.parsed.y}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                maintainAspectRatio: false
            }
        });

        const chartHour = new Chart(document.getElementById('chartHour'), {
            type: 'line',
            data: {
                labels: {!! json_encode($labelsHour) !!},
                datasets: [{
                    label: 'Response per Jam',
                    data: {!! json_encode($dataHour) !!},
                    fill: false,
                    borderColor: '#28a745',
                    tension: 0.2
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    }
                },
                maintainAspectRatio: false
            }
        });

        const chartRating = new Chart(document.getElementById('chartRating'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($labelsRating) !!},
                datasets: [{
                    data: {!! json_encode($dataRating) !!},
                    backgroundColor: ['#dc3545', '#fd7e14', '#ffc107', '#28a745', '#007bff']
                }]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 10
                        }
                    }
                },
                maintainAspectRatio: false
            }
        });
    </script>
@endsection
