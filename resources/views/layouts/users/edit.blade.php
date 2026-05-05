@extends('base')
@section('content')
<div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Edit User</h4>
                <p class="card-description"> Update User Details </p>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form class="forms-sample" method="POST" action="{{ route('admin.users.update', $user->user_id) }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" name="username" class="form-control" id="username" placeholder="Username" value="{{ old('username', $user->username) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="email" name="email" class="form-control" id="email" placeholder="Email" value="{{ old('email', $user->email) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="phone_number">Phone Number</label>
                        <input type="text" name="phone_number" class="form-control" id="phone_number" placeholder="Phone Number" value="{{ old('phone_number', $user->phone_number) }}">
                    </div>
                    <div class="form-group">
                        <label for="password">Password (Leave blank to keep current)</label>
                        <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select name="role" class="form-select" id="role" onchange="toggleParentFields()" required>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="teacher" {{ old('role', $user->role) == 'teacher' ? 'selected' : '' }}>Teacher</option>
                            <option value="mentor" {{ old('role', $user->role) == 'mentor' ? 'selected' : '' }}>Mentor</option>
                            <option value="parent" {{ old('role', $user->role) == 'parent' ? 'selected' : '' }}>Parent</option>
                        </select>
                    </div>

                    <!-- Additional Fields for Parent -->
                    <div id="parent-fields" style="display: {{ old('role', $user->role) == 'parent' ? 'block' : 'none' }};">
                        <div class="form-group">
                            <label for="student_id">Select Student</label>
                            <select name="student_id" class="form-select select2" id="student_id" style="width: 100%;">
                                <option value="">-- Select Student --</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->student_id }}" {{ old('student_id', optional($user->parent)->student_id) == $student->student_id ? 'selected' : '' }}>
                                        {{ $student->name_student }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name_parent">Parent Name</label>
                            <input type="text" name="name_parent" class="form-control" id="name_parent" placeholder="Full Name" value="{{ old('name_parent', optional($user->parent)->name_parent) }}">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary me-2">Update User</button>
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
@endsection
