@extends('layouts.app')

@section('content-app')
    <form action="" method="GET">
        <div class="row d-flex justify-content-end">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="tanggal">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control"
                        value="{{ request('tanggal') ?? date('Y-m-d') }}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                </div>
            </div>
            <div class="col-md-6 text-right">
                <div class="form-group">
                    <a href="{{ url('home') }}" class="btn btn-danger">
                        <i class="fa fa-refresh"></i> Reset</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-filter"></i> Filter</button>
                </div>
            </div>
        </div>
    </form>

    <div id="originalDashboardContainer">
        <div class="row" id="contentBox">
            {{-- load ajax content box --}}
        </div>


        <div class="row">

        </div>

        <div class="row">

        </div>
    </div>


    <!-- Modal Fullscreen Kosong -->
    <div id="fullscreenModal"
        style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:white; z-index:1050; overflow:auto; padding:30px; overflow-y: hidden">
        <button id="closeFullscreenBtn" style="display: none">
            <i class="fas fa-times"></i> Keluar Fullscreen
        </button>
        <div id="fullscreenContent"></div>
    </div>
@endsection

@section('js')
    <!-- Chart.js CDN -->
    <script src="{{ asset('theme/js') }}/chart.js"></script>
    <script src="{{ asset('theme/js') }}/chartjs-plugin-datalabels@2.js"></script>
@endsection
