<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="index.html">
                <i class="mdi mdi-grid-large menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <li class="nav-item nav-category">Management Reports</li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#form-elements" aria-expanded="false"
                aria-controls="form-elements">
                <i class="menu-icon mdi mdi-file-document"></i>
                <span class="menu-title">Report</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="form-elements">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a class="nav-link" href="pages/forms/basic_elements.html">Generate Report</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="pages/forms/basic_elements.html">Preview Report</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#mentors" aria-expanded="false"
                aria-controls="mentors">
                <i class=" menu-icon mdi mdi-human-male-board"></i>
                <span class="menu-title">Mentors</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="mentors">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a class="nav-link" href="pages/forms/basic_elements.html">Generate Student</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.students.index') }}">Preview Student</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#students" aria-expanded="false"
                aria-controls="students">
                <i class=" menu-icon mdi mdi-account-school-outline"></i>
                <span class="menu-title">Students</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="students">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.students.create') }}">Generate Student</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.students.index') }}">Preview Student</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#teachers" aria-expanded="false"
                aria-controls="teachers">
                <i class=" menu-icon mdi mdi-human-male-board-poll"></i>
                <span class="menu-title">Teachers</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="teachers">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a class="nav-link" href="pages/forms/basic_elements.html">Generate Teacher</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.students.index') }}">Preview Teacher</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#parents" aria-expanded="false"
                aria-controls="parents">
                <i class=" menu-icon mdi mdi-account-child-outline"></i>
                <span class="menu-title">Parents</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="parents">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a class="nav-link" href="pages/forms/basic_elements.html">Generate Parent</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.students.index') }}">Preview Parent</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#subjects" aria-expanded="false"
                aria-controls="subjects">
                <i class=" menu-icon mdi mdi-animation-outline"></i>
                <span class="menu-title">Subjects</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="subjects">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.subjects.create')}}">Add Subject</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.subjects.index') }}">Preview Subject</a>
                    </li>
                </ul>
            </div>
        </li>
        
       
        
        <li class="nav-item nav-category">Management Users</li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
                <i class="menu-icon mdi mdi-account-circle-outline"></i>
                <span class="menu-title">User Account</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="auth">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item"> <a class="nav-link" href="{{ route('admin.users.create')}}"> Add User </a>
                    </li>
                    <li class="nav-item"> <a class="nav-link" href="{{ route('admin.users.index')}}"> List Users </a></li>
                </ul>
            </div>
        </li>
    </ul>
</nav>
