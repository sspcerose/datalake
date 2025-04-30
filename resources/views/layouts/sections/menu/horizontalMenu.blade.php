<style>
    .nav-link.active {
        position: relative;
        color: #0d6efd !important; 
        font-weight: bold;
    }
    .nav-link.active::after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        height: 2px;
        width: 100%;
        background-color: #0d6efd;
        border-radius: 2px;
    }
    .navbar-sticky {
            position: sticky;
            top: 0; 
            /* z-index: 2;  */
        }
</style>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-12 navbar-sticky navbar-shadow">
    <div class="container-fluid py-2">

        <!-- Mobile Hamburger + Logo -->
        <div class="d-flex d-lg-none align-items-center">
            <button class="navbar-toggler border-0 me-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="{{ route('dashboard-analytics')}}">
                <!-- <img src="{{ asset('assets/img/favicon/DATA LAKE.png') }}" alt="Logo" style="height: 30px;"> -->
            </a>
        </div>

        <!-- Desktop Logo -->
        <a class="navbar-brand d-none d-lg-block" href="{{ route('dashboard-analytics')}}">
            <img src="{{ asset('assets/img/favicon/data-lake-logo.png') }}" alt="Logo" style="height: 40px;">
            Data Lake
        </a>

        <div class="d-none d-lg-block mx-2" style="border-left: 1px solid #ccc; height: 40px;"></div>

        <!-- Navigation Links -->
          <!-- ['icon' => 'bx-home', 'route' => 'dashboard-analytics', 'label' => 'Home', 'name' => 'Home', 'permission' => null],
                ['icon' => 'bx-cloud', 'route' => 'weather.index', 'label' => 'Weather', 'name' => 'Weather', 'permission' => 'View Weather'],
                ['icon' => 'bx-user', 'route' => 'user-management', 'label' => 'Users', 'name' => 'Users', 'permission' => 'View Users'],
                ['icon' => 'bx-lock-alt', 'route' => 'roles.index', 'label' => 'Permissions', 'name' => 'Permissions', 'permission' => 'View Roles'], -->
        @php
            $navItems = [
               
                ['route' => 'dashboard-analytics', 'label' => 'Home', 'name' => 'Home', 'permission' => null],
                ['route' => 'weather.index', 'label' => 'Weather Table', 'name' => 'Weather', 'permission' => 'View Weather'],
                ['route' => 'user-management', 'label' => 'Users', 'name' => 'Users', 'permission' => 'View Users'],
                ['route' => 'roles.index', 'label' => 'Roles and Permissions', 'name' => 'Permissions', 'permission' => 'View Roles'],
            ];
        @endphp

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-lg-3">
                @foreach ($navItems as $menu)
                    @if (
                        isset($menu['permission']) === false || 
                        (Auth::check() && Auth::user()->hasPermission($menu['permission']))
                    )
                        <li class="nav-item me-3">
                            <a class="nav-link d-flex align-items-center {{ request()->routeIs($menu['route']) ? 'active' : '' }}"
                            href="{{ route($menu['route']) }}">
                                {{ $menu['label'] }}
                                
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>



        <!-- <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-lg-3">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard-analytics') ? 'active' : '' }}" href="{{ route('dashboard-analytics') }}"> <i class='bx bx-home me-2'></i>Home</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('weather.index') ? 'active' : '' }}" href="{{ route('weather.index') }}"><i class='bx bx-cloud me-2'></i> Weather</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('user-management') ? 'active' : '' }}" href="{{ route('user-management') }}"> <i class='bx bx-user me-2'></i>Users</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('roles.index') ? 'active' : '' }}" href="{{ route('roles.index') }}"> <i class='bx bx-lock-alt me-2'></i> Permissions</a>
        </li>
    </ul>
        </div> -->

        @php
        use Illuminate\Support\Facades\DB;

        $jobsDone = DB::table('jobs_done')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        @endphp

        <!-- Profile and NotificationDropdown on Right -->
        <div class="d-flex align-items-center ms-auto gap-3">
        <!-- Notification Bell -->
        <div class="dropdown mx-2">
        <button class="btn border-0 position-relative p-0" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bx bx-bell fs-4" style="color:rgb(49, 112, 184);"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-danger text-white d-flex align-items-center justify-content-center" style="width: 18px; height: 18px; font-size: 11px;">
            {{ count($jobsDone) }}
            </span>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow border border-dark" aria-labelledby="notificationDropdown" style="min-width: 300px; max-height: 400px; overflow-y: auto;">
            <li class="dropdown-header text-center border-bottom border-dark">Notifications</li>
            @forelse($jobsDone as $jobDone)
            <li class="dropdown-item">
            <strong>Successfully added {{ number_format($jobDone->total_rows) }} rows to the database.</strong><br>
            <small class="text-muted">{{ \Carbon\Carbon::parse($jobDone->created_at)->format('F d, Y') }}</small>
            </li>
            <li><hr class="dropdown-divider"></li>
            @empty
            <li class="dropdown-item text-muted text-center">No notifications</li>
            @endforelse
            <!-- <li class="dropdown-item">
            <strong>File Failed to Import</strong><br>
            <small class="text-muted">March 11, 2025</small>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li class="dropdown-item">
            <strong>System Maintenance Scheduled</strong><br>
            <small class="text-muted">January 10, 2025</small>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li class="dropdown-item">
            <strong>New User Registration Approved</strong><br>
            <small class="text-muted">December 20, 2024</small>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li class="dropdown-item text-muted text-center">View all notifications</li> -->
        </ul>
        </div>

        <!-- User Dropdown -->
        <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" style="color: #4B7748;" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
            <!-- <i class="bx bx-user-circle fs-4 me-2" style="color: #4B7748;"></i> -->
            <span><strong>Hello, {{Auth::user()->first_name}}</strong></span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end text-small shadow" aria-labelledby="dropdownUser">
            <li>
            <a class="dropdown-item" href="{{ route('user-profile') }}">
                <i class="bx bx-user bx-md me-2"></i><span>My Profile</span>
            </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
            <form action="{{ route('logout') }}" method="GET">
                @csrf
                <button type="submit" class="dropdown-item text-danger d-flex align-items-center">
                <i class="bx bx-power-off bx-md me-2"></i><span>Log Out</span>
                </button>
            </form>
            </li>
        </ul>
        </div>
        </div>

</nav>
