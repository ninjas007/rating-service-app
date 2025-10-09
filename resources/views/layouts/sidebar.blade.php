<!--**********************************
            Sidebar start
        ***********************************-->
<div class="nk-sidebar">
    <div class="nk-nav-scroll">
        <ul class="metismenu" id="menu">
            <li class="nav-label text-center">SEBAGAI {{ ucfirst(auth()->user()->username) }}</li>
            <li>
                <a href="{{ url('/home') }}" aria-expanded="false">
                    <i class="fa fa-dashboard menu-icon"></i><span class="nav-text">Dashboard</span>
                </a>
            </li>

            <li>
                <a href="{{ url('/location') }}" aria-expanded="false">
                    <i class="fa fa-map menu-icon"></i><span class="nav-text">Lokasi</span>
                </a>
            </li>

            <li>
                <a href="{{ url('/question') }}" aria-expanded="false">
                    <i class="fa fa-list menu-icon"></i><span class="nav-text">Pertanyaan</span>
                </a>
            </li>

            <li>
                <a href="{{ url('/templates') }}" aria-expanded="false">
                    <i class="fa fa-file menu-icon"></i><span class="nav-text">Template</span>
                </a>
            </li>

            <li>
                <a href="{{ url('/survey') }}" aria-expanded="false">
                    <i class="fa fa-send menu-icon"></i><span class="nav-text">Publish Survey</span>
                </a>
            </li>

            <li>
                <a href="{{ url('/report-feedback') }}" aria-expanded="false">
                    <i class="fa fa-file menu-icon"></i><span class="nav-text">Laporan Feedback</span>
                </a>
            </li>

            <li class="nav-label">Pengaturan</li>

            {{-- @if (auth()->user()->role == 'supervisor' || auth()->user()->role == 'admin')
                <li>
                    <a href="{{ url('/users') }}" aria-expanded="false">
                        <i class="fa fa-users menu-icon"></i><span class="nav-text">Pengguna</span>
                    </a>
                </li>
            @endif --}}

            @if (auth()->user()->role == 'admin')
                <li>
                    <a href="{{ url('/setting') }}" aria-expanded="false">
                        <i class="fa fa-gears menu-icon"></i><span class="nav-text">Setting</span>
                    </a>
                </li>
            @endif

            {{-- divider --}}
            <div class="divider border mt-2"></div>

            {{-- logout --}}
            <li>
                <a href="javascript:void()" onclick="logout()" aria-expanded="false">
                    <i class="fa fa-sign-out menu-icon text-danger"></i><span class="nav-text text-danger">Logout</span>
                </a>
            </li>
        </ul>
    </div>
</div>


<form action="{{ url('/logout') }}" method="POST" id="logout-form">
    @csrf
</form>

<!--**********************************
    Sidebar end
***********************************-->
