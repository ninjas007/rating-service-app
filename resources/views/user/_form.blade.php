<form action="{{ isset($user) ? route('users.update', $user->uid) : route('users.store') }}" method="POST">
    @if (isset($user))
        @method('PUT')
    @endif
    @csrf

    <div class="row">
        <div class="col-md-6">
            <!-- Nama (Select dari daftar pegawai) -->
            <div class="form-group">
                <label for="name">Nama User</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="Masukkan Nama" required value="{{ isset($user) ? $user->name : old('name') }}">
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email"
                    class="form-control @error('email') is-invalid @enderror" placeholder="Masukkan email"
                    value="{{ isset($user) ? $user->email : old('email') }}">
                @error('email')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Username -->
            <div class="form-group">
                <label for="username">Username</label> <span class="text-danger font-weight-bold">*</span>
                <input type="text" name="username" id="username"
                    class="form-control @error('username') is-invalid @enderror" placeholder="Masukkan username"
                    required value="{{ isset($user) ? $user->username : old('username') }}">
                @error('username')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <!-- Role -->
            <div class="form-group">
                <label for="role">Role</label> <span class="text-danger font-weight-bold">*</span>
                <select name="role" id="role" class="form-control @error('role') is-invalid @enderror" required>
                    <option value="">-- Pilih Role --</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role['id'] }}"
                            {{ (isset($user) && $user->role == $role['id']) || old('role') == $role ? 'selected' : '' }}>
                            {{ ucfirst($role['name']) }}
                        </option>
                    @endforeach
                </select>
                @error('role')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <!-- Status -->
            <div class="form-group">
                <label for="status">Status</label> <span class="text-danger font-weight-bold">*</span>
                <select name="status" id="status" class="form-control @error('status') is-invalid @enderror"
                    required>
                    <option value="">-- Pilih Status --</option>
                    <option value="active"
                        {{ (isset($user) && $user->status == 'active') || old('status') == 'active' ? 'selected' : '' }}>
                        Aktif</option>
                    <option value="inactive"
                        {{ (isset($user) && $user->status == 'invactive') || old('status') == 'inactive' ? 'selected' : '' }}>
                        Tidak Aktif</option>
                </select>
                @error('status')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- password --}}
            <div class="form-group">
                <label for="password">Password</label> <span class="text-danger font-weight-bold">*</span>
                <input type="password" name="password" id="password"
                    class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan password"
                    value="{{ isset($user) ? $user->password : old('password') }}">
                @error('password')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <!-- Tombol -->
    <div class="form-group mt-4 text-right">
        <a href="{{ url('/users') }}" class="btn btn-sm btn-danger">
            <i class="fa fa-arrow-left"></i> Kembali
        </a>
        <button type="submit" class="btn btn-sm btn-primary">
            <i class="fa fa-save"></i> Simpan
        </button>
    </div>
</form>
