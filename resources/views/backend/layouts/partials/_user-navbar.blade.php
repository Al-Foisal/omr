<nav class="main-header navbar navbar-expand-md navbar-light navbar-white"
    style="top: 130px;margin-left: 0;position:inherit;margin-bottom:3rem;">
    <div class="container-fluide">

        <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse"
            aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse order-3" id="navbarCollapse">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                
                <li class="nav-item">
                    <a href="{{ route('admin.auth.studentDetails', request()->id) }}" class="nav-link">Enrolled Courses</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.auth.studentDetails', [request()->id, 'ct' => 'c']) }}"
                        class="nav-link">Confirm Courses</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.auth.studentDetails', [request()->id, 'ct' => 'p']) }}"
                        class="nav-link">Pending Courses</a>
                </li>
                <li class="nav-item">
                    <a href="" class="nav-link">Attend Exam</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
