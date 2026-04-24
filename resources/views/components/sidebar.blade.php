`<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
        @if(Auth::user()->isAllowed('web:Home:index') )
        <li class="nav-item">
            <a class="nav-link @yield('dashboard-active')" href="/home">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li><!-- End Dashboard Nav -->
        @endif
        @if (Auth::user()->isAllowed('web:Employee:showform') || Auth::user()->isAllowed('web:Employee:index'))
        <li class="nav-item @yield('people-management-active')">
            <a class="nav-link collapsed@yield('people-management-active')" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-menu-button-wide"></i>
                <span>People Management</span>
                <i class="bi bi-chevron-down ms-auto">

                </i>
            </a>
            <ul id="components-nav" class="nav-content collapse  @yield('people-management-active')" data-bs-parent="#sidebar-nav">

                @if(Auth::user()->isAllowed('web:Employee:showform'))
                <li>
                    <a href="{{route('addemployee')}}" class="@yield('people-management_addemp_active')">
                        <i class="bi bi-circle"></i><span>Add employee</span>
                    </a>
                </li>
                @endif
                @if (Auth::user()->isAllowed('web:Employee:index'))
                <li>
                    <a href="{{ route('employees.index') }}">

                        <i class="bi bi-circle"></i><span>Employees</span>
                    </a>
                </li>
                @endif
            </ul>

        </li>
        @endif
        <!-- Employee -->
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#attendance-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-vector-pen"></i><span>Attendance Management</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="attendance-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{route('create')}}">
                        <i class="bi bi-circle"></i><span>Employee Attendance</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('addattendance')}}">
                        <i class="bi bi-circle"></i><span>Add Attendance</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('view')}}">
                        <i class="bi bi-circle"></i><span>View Attendance</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('attendance.editable') }}">
                        <i class="bi bi-circle"></i><span>Edit Attendance (Table)</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('officetimingindex')}}">
                        <i class="bi bi-circle"></i><span>Office Timing</span>
                    </a>
                </li>
            </ul>
        </li>
        <!-- Attendance -->

        @if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('hr_manager'))
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#dailystandup-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-mic"></i><span>Daily Standup</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="dailystandup-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('dailystandup.create') }}">
                        <i class="bi bi-circle"></i><span>Add Emp Meeting</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('dailystandup.index') }}">
                        <i class="bi bi-circle"></i><span>Show Meeting List</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('dailystandup.manage') }}">
                        <i class="bi bi-circle"></i><span>Manage Meetings (Editable)</span>
                    </a>
                </li>
            </ul>
        </li>
        @endif
        <!-- Daily Standup -->
        @if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('hr_manager'))
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#performance-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-graph-up-arrow"></i><span>Performance Management</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="performance-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('performance.reviews.index') }}">
                        <i class="bi bi-circle"></i><span>Reviews</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('performance.appraisals.index') }}">
                        <i class="bi bi-circle"></i><span>Appraisals</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('performance.reports.index') }}">
                        <i class="bi bi-circle"></i><span>Reports</span>
                    </a>
                </li>
            </ul>
        </li>
        @endif
        <!-- Performance Management -->

        @if(Auth::user()->isAllowed('web:Leave:leavereqestindex') || Auth::user()->isAllowed('web:Leave:leavereqest') || Auth::user()->isAllowed('web:Leave:leaveform'))
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-journal-text"></i><span>Leave Management</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                @if(Auth::user()->isAllowed('web:Leave:leavereqestindex'))
                <li>
                    <a href="{{route('ApproveLeaves')}}">
                        <i class="bi bi-circle"></i><span>Approve Leaves</span>
                    </a>
                </li>
                @endif
                @if(Auth::user()->isAllowed('web:Leave:leavereqest'))
                <li>
                    <a href="{{route('LeavesStatus')}}">
                        <i class="bi bi-circle"></i><span>Leaves Status</span>
                    </a>
                </li>
                @endif
                @if(Auth::user()->isAllowed('web:Leave:leaveform'))
                <li>
                    <a href="{{route('ApplyLeave')}}">
                        <i class="bi bi-circle"></i><span>Apply leave</span>
                    </a>
                </li>
                @endif
            </ul>
        </li><!-- Leave -->
        @endif

        @if(Auth::user()->isAllowed('web:Sallary:salaryindex') || Auth::user()->isAllowed('web:SalaryIndex:index'))
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#charts-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-bar-chart"></i><span>Payments</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="charts-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                @if(Auth::user()->isAllowed('web:Sallary:salaryindex'))
                <li>
                    <a href="{{route('salary.index')}}">
                        <i class="bi bi-circle"></i><span>Salary</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('salary.store')}}">
                        <i class="bi bi-circle"></i><span>Salary Count</span>
                    </a>
                </li>
                @endif
            </ul>
        </li>
        @endif
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#office-policy-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-file-earmark-text"></i>
                <span>Office Policies</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>

            <ul id="office-policy-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('officepolicy.index') }}">
                        <i class="bi bi-circle"></i>
                        <span>All Policies</span>
                    </a>
                </li>
                @hasanyrole('admin|hr_manager')
                <li>
                    <a href="{{ route('officepolicy.create') }}">
                        <i class="bi bi-circle"></i>
                        <span>Add Policy</span>
                    </a>
                </li>
                @endhasanyrole
            </ul>
        </li>

        @hasanyrole('admin|hr_manager')
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#employee-interview-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-clipboard-check"></i>
                <span>Employee Interviews</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>

            {{-- Child Menu --}}
            <ul id="employee-interview-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{ route('employeeinterviews.index') }}">
                        <i class="bi bi-people"></i>
                        <span>All Interviews</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('employeeinterviews.create') }}">
                        <i class="bi bi-people"></i>
                        <span>Add Interview</span>
                    </a>
                </li>

            </ul>
        </li>
        @endhasanyrole
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-layout-text-window-reverse"></i><span>Settings</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="{{route('totalleaves')}}">
                        <i class="bi bi-circle"></i><span>Leaves</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('designationlist')}}">
                        <i class="bi bi-circle"></i><span>Designation</span>
                    </a>
                </li>
                <li>
                    <a href="/full-calender">
                        <i class="bi bi-circle"></i><span>Events</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('eventform')}}">
                        <i class="bi bi-circle"></i><span>Add Events</span>
                    </a>
                </li>
                <li>
                    <a href="{{route('signature')}}">
                        <i class="bi bi-circle"></i><span>Add Signature</span>
                    </a>
                </li>
                @if(Auth::user()->isAllowed('web:Roles:show'))
                <li>
                    <a href="{{route('create.Roles')}}">
                        <i class="bi bi-circle"></i><span>Roles</span>
                    </a>
                </li>
                @endif
            </ul>
        </li><!-- Setting -->
        <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#trello-nav" data-bs-toggle="collapse" href="#">
    <i class="bi bi-kanban"></i>
    <span>BLS Trello</span>
    <i class="bi bi-chevron-down ms-auto"></i>
</a>

            <ul id="trello-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">

                <li>
                    <a
                        href="{{ route('projects.index') }}">
                        <i class="bi bi-circle"></i> Projects
                    </a>
                </li>
                <li>
                    <a href="{{ route('projects.index') }}">
                        <i class="bi bi-circle"></i> Tasks
                    </a>
                </li>
                <li>
                    <a
                        href="{{ route('routines.index') }}">
                        <i class="bi bi-circle"></i> Routines
                    </a>
                </li>
                <li>
                    <a href="{{ route('notes.index') }}">
                        <i class="bi bi-circle"></i> Notes
                    </a>
                </li>
                <li>
                    <a
                        href="{{ route('reminders.index') }}">
                        <i class="bi bi-circle"></i> Reminders
                    </a>
                </li>
                <li>
                    <a href="{{ route('files.index') }}">
                        <i class="bi bi-circle"></i> Files
                    </a>
                </li>
            </ul>
        </li><!-- Setting -->
</aside>
