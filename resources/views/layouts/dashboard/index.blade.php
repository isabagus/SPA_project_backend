@extends('base')
@section('content')
    <h1 class="welcome-text"><span class="text-black fw-bold">Dashboard Admin</span></h1>
    <div class="d-sm-flex align-items-center justify-content-between border-bottom">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active ps-0" id="home-tab" data-bs-toggle="tab" href="#overview" role="tab"
                    aria-controls="overview" aria-selected="true">School Overview</a>
            </li>
        </ul>
    </div>
    <div class="tab-content tab-content-basic">
        <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
            <div class="row">
                <div class="col-sm-12">
                    <div class="statistics-details d-flex align-items-center justify-content-between">
                        <div>
                            <p class="statistics-title">Total Students</p>
                            <h3 class="rate-percentage">{{ $stats['total_students'] }}</h3>
                            <p class="text-success d-flex"><i class="mdi mdi-account-multiple"></i><span>Active Students</span></p>
                        </div>
                        <div>
                            <p class="statistics-title">Total Mentors</p>
                            <h3 class="rate-percentage">{{ $stats['total_mentors'] }}</h3>
                            <p class="text-primary d-flex"><i class="mdi mdi-account-star"></i><span>Class Mentors</span></p>
                        </div>
                        <div>
                            <p class="statistics-title">Total Teachers</p>
                            <h3 class="rate-percentage">{{ $stats['total_teachers'] }}</h3>
                            <p class="text-info d-flex"><i class="mdi mdi-school"></i><span>Subject Teachers</span></p>
                        </div>
                        <div class="d-none d-md-block">
                            <p class="statistics-title">Total Classes</p>
                            <h3 class="rate-percentage">{{ $stats['total_classes'] }}</h3>
                            <p class="text-warning d-flex"><i class="mdi mdi-home-variant"></i><span>Level Classes</span></p>
                        </div>
                        <div class="d-none d-md-block">
                            <p class="statistics-title">Total Subjects</p>
                            <h3 class="rate-percentage">{{ $stats['total_subjects'] }}</h3>
                            <p class="text-danger d-flex"><i class="mdi mdi-book-open-variant"></i><span>Active Subjects</span></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 d-flex flex-column">
                    <div class="row flex-grow">
                        <div class="col-12 grid-margin stretch-card">
                            <div class="card card-rounded">
                                <div class="card-body">
                                    <div class="d-sm-flex justify-content-between align-items-start">
                                        <div>
                                            <h4 class="card-title card-title-dash">Quick Actions</h4>
                                            <p class="card-subtitle card-subtitle-dash">Common administrative tasks</p>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-3">
                                            <a href="{{ route('admin.students.create') }}" class="btn btn-primary btn-lg text-white mb-0 me-0 w-100">
                                                <i class="mdi mdi-account-plus"></i> Add Student
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="{{ route('admin.teachers.create') }}" class="btn btn-info btn-lg text-white mb-0 me-0 w-100">
                                                <i class="mdi mdi-school"></i> Add Teacher
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="{{ route('admin.subjects.create') }}" class="btn btn-success btn-lg text-white mb-0 me-0 w-100">
                                                <i class="mdi mdi-book-plus"></i> Add Subject
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="{{ route('admin.mentors.setClass') }}" class="btn btn-warning btn-lg text-white mb-0 me-0 w-100">
                                                <i class="mdi mdi-settings"></i> Assign Mentors
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
