@extends('layouts.app')

@section('content-app')
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5>Edit Profile</h5>
                </div>
                <div class="card-body pt-0">
                    <form action="{{ url()->current() }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="name">Nama</label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ auth()->user()->name }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username"
                                value="{{ auth()->user()->username }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="current_password">Password Sekarang</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="current_password" name="current_password"
                                    required>
                                <div class="input-group-append">
                                    <span class="input-group-text toggle-password" data-target="#current_password">
                                        <i class="fa fa-eye"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="new_password">Password Baru</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="new_password" name="new_password">
                                <div class="input-group-append">
                                    <span class="input-group-text toggle-password" data-target="#new_password">
                                        <i class="fa fa-eye"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary float-right">
                           <i class="fa fa-save"></i> Save Changes
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        document.querySelectorAll('.toggle-password').forEach(element => {
            element.addEventListener('click', function() {
                const target = document.querySelector(this.dataset.target);
                if (target.type === "password") {
                    target.type = "text";
                } else {
                    target.type = "password";
                }
            });
        });
    </script>
@endsection
