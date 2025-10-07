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

            <li class="nav-label">Pengaturan</li>

            @if (auth()->user()->role == 'supervisor' || auth()->user()->role == 'admin')
                <li>
                    <a href="{{ url('/users') }}" aria-expanded="false">
                        <i class="fa fa-users menu-icon"></i><span class="nav-text">Users</span>
                    </a>
                </li>
            @endif

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
