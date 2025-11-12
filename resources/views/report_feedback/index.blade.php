@extends('layouts.app')

@section('content-app')
    <h4><i class="fa fa-chart-bar"></i> Laporan Kepuasan Pelanggan</h4>

    <form method="GET" class="mb-3 d-flex align-items-center gap-2">
        <div class="form-group">
            <input type="date" name="tanggal" value="{{ $tanggal }}" class="form-control mr-1" style="width:200px;">
        </div>
        <div class="form-group">
            <button class="btn btn-primary">Filter</button>
            <button type="button" class="btn btn-danger" onclick="window.location.href = `{{ url('report-feedback') }}`">Reset</button>
        </div>
        <div class="form-group">
            <a href="{{ route('report-feedback.pdf', ['tanggal' => $tanggal]) }}" target="_blank" class="btn btn-success text-white ml-1">
                <i class="fa fa-save"></i> Export PDF
            </a>
        </div>
        {{-- <a href="{{ route('report-feedback.excel', ['tanggal' => $tanggal]) }}" class="btn btn-success">
            <i class="fa fa-file-excel"></i> Export Excel
        </a> --}}
    </form>

    <div class="card">
        <div class="card-header">
            Laporan Kepuasan Pelanggan
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="bg-primary text-white">
                    <tr>
                        {{-- <th>No</th> --}}
                        <th>Area</th>
                        <th>Tidak puas</th>
                        <th>Puas</th>
                        <th>Sangat puas</th>
                        {{-- <th>Very Bad</th>
                        <th>Bad</th>
                        <th>Neutral</th>
                        <th>Good</th>
                        <th>Very Good</th> --}}
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $i => $row)
                        <tr>
                            {{-- <td>{{ $i + 1 }}</td> --}}
                            <td>{{ $row->area }}</td>
                            <td>{{ $row->bad }}</td>
                            <td>{{ $row->good }}</td>
                            <td>{{ $row->very_good }}</td>
                            {{-- <td>{{ $row->neutral }}</td> --}}
                            {{-- <td>{{ $row->good }}</td>
                            <td>{{ $row->very_good }}</td> --}}
                            <td class="fw-bold">{{ $row->total }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
