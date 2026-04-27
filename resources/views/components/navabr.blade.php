<header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
        <a href="/home" class="logo d-flex align-items-center">
            <img src="/assets/img/logo.png" alt="">
            <span class="d-none d-lg-block">BLS Sol</span>
        </a>
        <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">

             <!--
            <li class="nav-item d-block">
                <a class="nav-link nav-icon search-bar-toggle " href="#">
                    <i class="bi bi-search"></i>
                </a>
            </li>
        -->
            <!-- End Search Icon-->

            <!-- Notifications -->
            <li class="nav-item dropdown">
                <!-- Notifications -->
                
            <li class="nav-item dropdown">
                 <!--
                <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
                    <i class="bi bi-bell"></i>
                    <span class="badge bg-primary badge-number">4</span>
                </a>
            -->
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
                    <li class="dropdown-header">
                        You have 4 new notifications
                        <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <!-- Notification items... -->
                </ul>
            </li><!-- End Notification Nav -->

            <!-- Messages -->
            <li class="nav-item dropdown">
                 <!--
                <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
                    <i class="bi bi-chat-left-text"></i>
                    <span class="badge bg-success badge-number">3</span>
                </a>
            -->
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages">
                    <li class="dropdown-header">
                        You have 3 new messages
                        <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <!-- Message items... -->
                </ul>
            </li><!-- End Messages Nav -->

            <!-- Profile -->
            @auth
            @php
            $user = Auth::user();
            $employee = $user?->employee;
            @endphp

            <li class="nav-item dropdown pe-3">

                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                    @if($user->image)
                    <img src="{{ asset('storage/assets/profile_images/' . $user->image) }}" alt="Profile" />
                    @else
                    <img src="{{ asset('storage/assets/profile_image/img2.jpeg') }}" alt="Profile" />
                    @endif

                    <span class="d-none d-md-block dropdown-toggle ps-2">
                        {{ $user->name }}
                    </span>
                </a>

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                    <li class="dropdown-header">
                        <h6>{{ $user->name }}</h6>
                        <span>{{ $user->roles->pluck('name')->first() }}</span>
                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    
                    @hasanyrole('admin|hr_manager|employee|sqa|developer|qa')
<li>
    <a class="dropdown-item d-flex align-items-center"
        href="{{ route('my.profile') }}">
        <i class="bi bi-person"></i>
        <span>My Profile</span>
    </a>
</li>
@endhasanyrole

                    <li>
                        <hr class="dropdown-divider">
                    </li>
@if($employee)
                    <li>
                        <a class="dropdown-item d-flex align-items-center"
                            href="{{ route('accountsetting.show', $employee->id) }}">
                            <i class="bi bi-gear"></i>
                            <span>Account Settings</span>
                        </a>
                    </li>
                    @endif

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="#"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Sign Out</span>
                        </a>
                    </li>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </ul>
            </li>
            @endauth

        </ul><!-- End Profile Dropdown Items -->

    </nav><!-- End Icons Navigation -->
</header><!-- End Header -->