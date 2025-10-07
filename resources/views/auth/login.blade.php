    @extends('auth.template')

    @section('styles')
        <style>
            .login-form-bg {
                background-image: url('{{ asset('images/background.png') }}?v={{ time() }}') !important;
                background-size: cover !important;
                background-position: center !important;
            }
        </style>
    @endsection

    @section('content')
        <div class="login-form-bg h-100">
            <div class="container h-100">
                <div class="row justify-content-center h-100">
                    <div class="col-xl-5">
                        <div class="form-input-content">
                            <div class="card login-form mb-0" style="opacity: 0.85">
                                <div class="card-body">
                                    <div class="text-center">
                                        <img src="{{ asset('images/gambar-login.png') }}?v={{ time() }}" alt=""
                                            style="width: {{ $logoSize }}px; height: {{ $logoSize }}px; background-size: contain; border-radius: 20px">
                                        <br>
                                        <br>
                                        <h4>Login</h4>
                                    </div>
                                    <form id="loginForm" class="mt-2 mb-3 login-input" method="POST"
                                        action="{{ route('login') }}">
                                        @csrf
                                        <div class="form-group mb-2">
                                            <input type="text" id="username" class="form-control" name="username"
                                                placeholder="Username" style="border-bottom: 0.5px solid #a2a2a2"
                                                autocomplete="off" autofocus>
                                            @error('username')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <input type="password" id="password" class="form-control" name="password"
                                                placeholder="Password" style="border-bottom: 0.5px solid #a2a2a2">
                                            @error('password')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <button type="submit" class="btn login-form__btn submit w-100">
                                            <i class="fa fa-sign-in"></i> Sign In
                                        </button>
                                    </form>
                                </div>
                                <div class="card-footer text-center">
                                    Supported by Bio Experience Indonesia
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @section('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const form = document.getElementById("loginForm");
                const username = document.getElementById("username");
                const password = document.getElementById("password");

                function handleEnter(e) {
                    if (e.key === "Enter") {
                        e.preventDefault(); // cegah submit default

                        if (!username.value.trim()) {
                            username.focus();
                        } else if (!password.value.trim()) {
                            password.focus();
                        } else {
                            form.submit(); // submit kalau 2-2 nya terisi
                        }
                    }
                }

                username.addEventListener("keydown", handleEnter);
                password.addEventListener("keydown", handleEnter);
            });
        </script>
    @endsection
