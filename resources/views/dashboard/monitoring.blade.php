<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <!-- theme meta -->
    <meta name="theme-name" content="quixlab" />

    {{-- meta csrf token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Laboratory Information System - {{ strtoupper($page) ?? 'Home' }}</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/logo.png') }}">
    <!-- Pignose Calender -->
    <link href="{{ asset('theme') }}/plugins/pg-calendar/css/pignose.calendar.min.css" rel="stylesheet">
    <!-- Chartist -->
    <link rel="stylesheet" href="{{ asset('theme') }}/plugins/chartist/css/chartist.min.css">
    <link rel="stylesheet" href="{{ asset('theme') }}/plugins/chartist-plugin-tooltips/css/chartist-plugin-tooltip.css">

    <!-- Toastr CSS -->
    <link href="{{ asset('theme') }}/css/toastr.min.css" rel="stylesheet">

    <link href="{{ asset('theme') }}/css/select2.min.css" rel="stylesheet" />

    <!-- Datatable -->
    <link href="{{ asset('theme') }}/plugins/tables/css/datatable/dataTables.bootstrap4.min.css" rel="stylesheet">

    <!-- Custom Stylesheet -->
    <link href="{{ asset('theme') }}/css/style.css" rel="stylesheet">

<body style="overflow: hidden">
    <audio id="notify-sound" src="{{ asset('theme/sound/bell.mp3') }}" preload="auto"></audio>
    <!--*******************
        Preloader start
            ********************-->
    <div id="preloader">
        <div class="loader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3"
                    stroke-miterlimit="10" />
            </svg>
        </div>
    </div>
    <!--*******************
                Preloader end
            ********************-->

    <div id="originalDashboardContainer" class="m-3">
        <div class="row mb-4">
            <div class="col-12">
                <div class="bg-primary text-white py-2 px-3 rounded">
                    <div class="row align-items-center">

                        <!-- Logo dan Marquee -->
                        <div class="col-md-8 d-flex align-items-center" style="gap: 10px;">
                            <img src="{{ asset('images/logors.png') }}?v={{ time() }}"
                                style="width: 55px; height: 55px; object-fit: contain">

                            <marquee behavior="scroll" direction="left" scrollamount="{{ $speedTextMonitoring ?? 8 }}"
                                style="flex: 1; font-weight: bold; font-size: 20px">
                                {!! $textMonitoring ?? '' !!}
                            </marquee>
                        </div>

                        <!-- Jam dan Tanggal -->
                        <div class="col-md-4 text-md-end text-right">
                            <div id="dateClock" style="font-size: 20px; font-weight: bold"></div>
                            <div style="font-size: 20px;">
                                <i class="fa fa-clock-o"></i>
                                <span id="timeClock" style="font-weight: bold">00:00:00</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-6" id="contentBox">
                {{-- load ajax content box --}}
            </div>
            <div class="col-md-6" id="contentBoxKritis">
                {{-- load ajax content kritis --}}
            </div>
        </div>

        <div class="row">
            <!-- Kunjungan per Jam -->
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white" style="font-size: 16px; font-weight: bold;">KUNJUNGAN /
                        PEMERIKSAAN PER JAM</div>
                    <div class="card-body">
                        <div class="chart-loading"
                            style="display:none; position:absolute; top:0; left:0; right:0; bottom:0; background:rgba(255,255,255,0.8); z-index:10; text-align:center; padding-top:80px;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <p>Memuat grafik...</p>
                        </div>
                        <canvas id="chartPerJam" height="200"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white" style="font-size: 16px; font-weight: bold;">PEMERIKSAAN
                        BERDASARKAN JENIS KELAMIN</div>
                    <div class="card-body">
                        <div class="chart-loading"
                            style="display:none; position:absolute; top:0; left:0; right:0; bottom:0; background:rgba(255,255,255,0.8); z-index:10; text-align:center; padding-top:80px;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <p>Memuat grafik...</p>
                        </div>
                        <canvas id="chartGender" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Pemeriksaan Belum Selesai -->
            <div class="col-md-7">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white" style="font-size: 16px; font-weight: bold;">PEMERIKSAAN
                        BERDASARKAN RUANGAN</div>
                    <div class="card-body">
                        <div class="chart-loading"
                            style="display:none; position:absolute; top:0; left:0; right:0; bottom:0; background:rgba(255,255,255,0.8); z-index:10; text-align:center; padding-top:80px;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <p>Memuat grafik...</p>
                        </div>
                        <canvas id="chartRuangan" height="200"></canvas>
                    </div>
                </div>
            </div>

            <!-- Distribusi Tempat Pemeriksaan -->
            <div class="col-md-5 mb-3">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white" style="font-size: 16px; font-weight: bold;">PEMERIKSAAN
                        BERDASARKAN ALAT</div>
                    <div class="card-body">
                        <div class="chart-loading"
                            style="display:none; position:absolute; top:0; left:0; right:0; bottom:0; background:rgba(255,255,255,0.8); z-index:10; text-align:center; padding-top:80px;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            <p>Memuat grafik...</p>
                        </div>
                        <canvas id="chartAlat" height="200"></canvas>
                    </div>
                </div>
            </div>

        </div>
    </div>

</body>

<script src="{{ asset('theme') }}/js/jquery-3.7.1.min.js"></script>

<script src="{{ asset('theme') }}/plugins/common/common.min.js"></script>
<script src="{{ asset('theme') }}/js/custom.min.js"></script>
<script src="{{ asset('theme') }}/js/settings.js"></script>
<script src="{{ asset('theme') }}/js/gleek.js"></script>
<script src="{{ asset('theme') }}/js/styleSwitcher.js"></script>

<!-- Chart.js CDN -->
<script src="{{ asset('theme/js') }}/chart.js"></script>
<script src="{{ asset('theme/js') }}/chartjs-plugin-datalabels@2.js"></script>
@include('dashboard._chart-js');

<script>
    let chartRuangan = null;
    let chartPerJam = null;
    let chartGender = null;
    let chartAlat = null;

    // ajax get data
    function getData() {
        $.ajax({
            url: "{{ url('/monitoring') }}",
            type: "GET",
            data: {
                tanggal: $('#tanggal').val(),
            },
            // beforeSend: function () {
            //     $('.chart-loading').show();
            // },
            dataType: "json",
            success: function(res) {
                $('.chart-loading').hide();
                $('#contentBox').html(res.htmlContentBox);
                $('#contentBoxKritis').html(res.htmlContentBoxKritis);

                getChartJs(res);
            }
        });
    }

    function getTime() {
        const now = new Date();

        const timeString = now.toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: true
        });

        // Format tanggal lokal dengan nama hari dan bulan
        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const dateString = days[now.getDay()] + ', ' + now.getDate() + ' ' +
            now.toLocaleString('id-ID', {
                month: 'long'
            }) + ' ' + now.getFullYear();

        $('#timeClock').html(timeString);
        $('#dateClock').html(dateString);
    }


    // Jalankan pertama kali dan ulangi setiap 2 detik
    getData();
    setInterval(getData, 1900);
    setInterval(getTime, 1000);
</script>

@include('layouts.pusher-js');
