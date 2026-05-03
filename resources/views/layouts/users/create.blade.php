@extends('base')
@section('content')
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">User Form Input</h4>
                <p class="card-description"> Add User </p>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form class="forms-sample" method="POST" action="{{ route('admin.users.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" name="username" class="form-control" id="username" placeholder="Username" value="{{ old('username') }}">
                    </div>
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="email" name="email" class="form-control" id="email" placeholder="Email" value="{{ old('email') }}">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select name="role" class="form-select" id="role" onchange="toggleParentFields()">
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="teacher" {{ old('role') == 'teacher' ? 'selected' : '' }}>Teacher</option>
                            <option value="mentor" {{ old('role') == 'mentor' ? 'selected' : '' }}>Mentor</option>
                            <option value="parent" {{ old('role') == 'parent' ? 'selected' : '' }}>Parent</option>
                        </select>
                    </div>

                    <!-- Additional Fields for Parent -->
                    <div id="parent-fields" style="display: {{ old('role') == 'parent' ? 'block' : 'none' }};">
                        <div class="form-group">
                            <label for="student_id">Select Student</label>
                            <select name="student_id" class="form-select select2" id="student_id" style="width: 100%;">
                                <option value="">-- Select Student --</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->student_id }}" {{ old('student_id') == $student->student_id ? 'selected' : '' }}>
                                        {{ $student->name_student }} ({{ $student->nis }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name_parent">Parent Name</label>
                            <input type="text" name="name_parent" class="form-control" id="name_parent" placeholder="Full Name" value="{{ old('name_parent') }}">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary me-2">Submit</button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-light">Cancel</a>
                </form>

                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
                <script>
                    $(document).ready(function() {
                        $('.select2').select2({
                            placeholder: "-- Select Student --",
                            allowClear: true
                        });
                    });

                    function toggleParentFields() {
                        const role = document.getElementById('role').value;
                        const parentFields = document.getElementById('parent-fields');
                        if (role === 'parent') {
                            parentFields.style.display = 'block';
                        } else {
                            parentFields.style.display = 'none';
                        }
                    }
                </script>
            </div>
        </div>
    </div>
@endsection()
