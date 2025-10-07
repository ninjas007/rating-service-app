@extends('layouts.app')

@section('content-app')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-header">Gambar Login</h4>
                    <div>Gambar Halaman Login (.png)</div>
                    <div class="text-center mt-2">
                        <img src="{{ asset('images/gambar-login.png') }}?v={{ time() }}" alt=""
                            style="width: 50%; height: 150px; background-size: contain;">
                    </div>
                </div>
                <div class="card-body pt-0">
                    <form action="{{ url('/setting/general') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-3">
                            <input type="file" class="form-control @error('gambar') is-invalid @enderror" id="gambar"
                                name="gambar" accept="image/png" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-sm float-right">
                                <i class="fa fa-save"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-header">Background Login</h4>
                    <div>Background Halaman Login (.png)</div>
                    <div class="text-center mt-2">
                        <img src="{{ asset('images/background.png') }}?v={{ time() }}" alt=""
                            style="width: 50%; height: 150px; background-size: contain;">
                    </div>
                </div>
                <div class="card-body pt-0">
                    <form action="{{ url('/setting/general') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-3">
                            <input type="file" class="form-control @error('background') is-invalid @enderror"
                                id="background" name="background" accept="image/png" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-sm float-right">
                                <i class="fa fa-save"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-header">Logo Menu</h4>
                    <div>Logo di menu sidebar (.png)</div>
                    <div class="text-center mt-2">
                        <img src="{{ asset('images/logo.png') }}?v={{ time() }}" alt=""
                            style="width: 50%; height: 150px; background-size: contain;">
                    </div>
                </div>
                <div class="card-body pt-0">
                    <form action="{{ url('/setting/general') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-3">
                            <input type="file" class="form-control @error('logo') is-invalid @enderror" id="logo"
                                name="logo" accept="image/png" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-sm float-right">
                                <i class="fa fa-save"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-header">Logo Aplikasi</h4>
                    <div>Logo aplikasi (.png)</div>
                    <div class="text-center mt-2">
                        <img src="{{ asset('images/logors.png') }}?v={{ time() }}" alt=""
                            style="width: 50%; height: 150px; background-size: contain;">
                    </div>
                </div>
                <div class="card-body pt-0">
                    <form action="{{ url('/setting/general') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-3">
                            <input type="file" class="form-control @error('logors') is-invalid @enderror" id="logors"
                                name="logors" accept="image/png" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-sm float-right">
                                <i class="fa fa-save"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-header">Setting Umum</h4>
                    <div>Konfigurasi dan setting sistem</div>
                </div>
                <div class="card-body">
                    <form action="{{ url('/setting/general') }}" method="POST">
                        @csrf

                        <div class="row">

                            <div class="col-md-3 form-group mb-3">
                                <label for="nama_perusahaan" class="form-label">Nama Perusahaan <i
                                        class="fa fa-info-circle" data-toggle="tooltip" data-placement="top"
                                        title="Nama perushaan yang akan ditampilkan di rating"></i></label>
                                @php
                                    $np = $setting->where('key', 'nama_perusahaan')->first();
                                @endphp
                                <input type="text" class="form-control @error('nama_perusahaan') is-invalid @enderror"
                                    id="nama_perusahaan" name="nama_perusahaan"
                                    value="{{ $np['value']->value ?? old('nama_perusahaan') }}">
                            </div>

                            <div class="col-md-3 form-group mb-3">
                                <label for="alamat_perusahaan" class="form-label">Alamat Perusahaan <i
                                        class="fa fa-info-circle" data-toggle="tooltip" data-placement="top"
                                        title="Alamat Perusahaan yang akan ditampilkan di rating"></i></label>
                                @php
                                    $ap = $setting->where('key', 'alamat_perusahaan')->first();
                                @endphp
                                <input type="text"
                                    class="form-control @error('alamat_perusahaan') is-invalid @enderror"
                                    id="alamat_perusahaan" name="alamat_perusahaan"
                                    value="{{ $ap['value']->value ?? old('alamat_perusahaan') }}">
                            </div>

                            <div class="col-md-3 form-group mb-3">
                                <label for="size_logo_login" class="form-label">Size Logo Login (px) <i
                                        class="fa fa-info-circle" data-toggle="tooltip" data-placement="top"
                                        title="settingan untuk size logo halaman login"></i></label>
                                @php
                                    $sizelogo = $setting->where('key', 'size_logo_login')->first();
                                @endphp
                                <input type="text" class="form-control @error('size_logo_login') is-invalid @enderror"
                                    id="size_logo_login" name="size_logo_login"
                                    value="{{ $sizelogo['value']->value ?? old('size_logo_login') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-sm float-right">
                                <i class="fa fa-save"></i> Simpan
                            </button>
                        </div>
                </div>
            </div>
        </div>
    </div>
@endsection
