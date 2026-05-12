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
                    
                    <!-- STEP 1: ROLE SELECTION -->
                    <div class="form-group mb-4">
                        <label for="role" class="fw-bold text-primary">1. User Role</label>
                        <select name="role" class="form-select form-control-lg border-primary" id="role" onchange="toggleProfileFields()">
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="teacher" {{ old('role', $user->role) == 'teacher' ? 'selected' : '' }}>Teacher</option>
                            <option value="mentor" {{ old('role', $user->role) == 'mentor' ? 'selected' : '' }}>Mentor</option>
                            <option value="parent" {{ old('role', $user->role) == 'parent' ? 'selected' : '' }}>Parent</option>
                        </select>
                    </div>

                    <hr>

                    <!-- STEP 2: PROFILE DETAILS (DYNAMIC) -->
                    <div id="profile-details-section">
                        <h5 class="mb-3 fw-bold">2. Profile Information</h5>
                        
                        <!-- Parent Fields -->
                        <div id="parent-fields" class="profile-fields" style="display: {{ old('role', $user->role) == 'parent' ? 'block' : 'none' }};">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="filter_year" class="fw-bold">1. Year (Academic Year)</label>
                                        <select id="filter_year" class="form-select border-primary">
                                            <option value="">-- All Years --</option>
                                            @foreach($academicYears as $year)
                                                <option value="{{ $year->academic_year }}" {{ optional($user->parent->student ?? null)->academic_year == $year->academic_year ? 'selected' : '' }}>
                                                    {{ $year->academic_year }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="filter_class" class="fw-bold">2. Class Level</label>
                                        <select id="filter_class" class="form-select border-primary">
                                            <option value="">-- All Classes --</option>
                                            @foreach($classes as $class)
                                                <option value="{{ $class->class_id }}" {{ optional($user->parent->student ?? null)->class_id == $class->class_id ? 'selected' : '' }}>
                                                    {{ $class->level_class ?? $class->level_name ?? 'Unspecified' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div id="student-selection-container">
                                <div class="form-group">
                                    <label for="student_id" class="fw-bold text-success">Select Student (Available / Assigned)</label>
                                    <select name="student_id" class="form-select select2" id="student_id" style="width: 100%;">
                                        <option value="">-- Search Available Students --</option>
                                        @foreach($students as $student)
                                            <option value="{{ $student->student_id }}" 
                                                    data-year="{{ $student->academic_year }}" 
                                                    data-class="{{ $student->class_id }}"
                                                    {{ old('student_id', optional($user->parent)->student_id) == $student->student_id ? 'selected' : '' }}>
                                                {{ $student->name_student }} ({{ $student->academic_year }} - {{ $student->level_class }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Type student name to search after filtering.</small>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name_parent">Parent Name</label>
                                <input type="text" name="name_parent" class="form-control" id="name_parent" placeholder="Full Name" value="{{ old('name_parent', optional($user->parent)->name_parent) }}">
                            </div>
                        </div>

                        <!-- Teacher Fields -->
                        <div id="teacher-fields" class="profile-fields" style="display: {{ old('role', $user->role) == 'teacher' ? 'block' : 'none' }};">
                            <div class="form-group">
                                <label for="name">Teacher Name</label>
                                <input type="text" name="name" class="form-control" id="name" placeholder="Full Name" value="{{ old('name', optional($user->teacher)->name) }}">
                            </div>
                        </div>

                        <!-- Mentor Fields -->
                        <div id="mentor-fields" class="profile-fields" style="display: {{ old('role', $user->role) == 'mentor' ? 'block' : 'none' }};">
                            <div class="form-group">
                                <label for="name_mentor">Mentor Name</label>
                                <input type="text" name="name_mentor" class="form-control" id="name_mentor" placeholder="Full Name" value="{{ old('name_mentor', optional($user->mentor)->name_mentor) }}">
                            </div>
                            <div class="form-group">
                                <label for="nip">NIP (Optional)</label>
                                <input type="text" name="nip" class="form-control" id="nip" placeholder="NIP" value="{{ old('nip', optional($user->mentor)->nip) }}">
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- STEP 3: ACCOUNT ACCESS -->
                    <div id="account-access-section">
                        <h5 class="mb-3 fw-bold">3. Account Credentials</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input type="text" name="username" class="form-control" id="username" placeholder="Username" value="{{ old('username', $user->username) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email address</label>
                                    <input type="email" name="email" class="form-control" id="email" placeholder="Email" value="{{ old('email', $user->email) }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">Password (Leave blank to keep current)</label>
                                    <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone_number">Phone Number</label>
                                    <input type="text" name="phone_number" class="form-control" id="phone_number" placeholder="Phone Number" value="{{ old('phone_number', $user->phone_number) }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary me-2">Update User</button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-light">Cancel</a>
                </form>

                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
                <script>
                    $(document).ready(function() {
                        const studentSelect = $('#student_id');
                        const allOptions = studentSelect.find('option').clone();
                        const initialSelectedValue = studentSelect.val();

                        $('.select2').select2({
                            placeholder: "-- Select Student --",
                            allowClear: true
                        });

                        function filterStudents() {
                            const selectedYear = $('#filter_year').val();
                            const selectedClass = $('#filter_class').val();
                            const studentContainer = $('#student-selection-container');

                            // Clear current options
                            studentSelect.empty();
                            
                            // Always add the placeholder option
                            studentSelect.append('<option value="">-- Search Available Students --</option>');

                            // Filter and append matching options
                            let matchCount = 0;
                            allOptions.each(function() {
                                const option = $(this);
                                const optValue = option.val();
                                if (!optValue) return;

                                const matchesYear = !selectedYear || option.data('year') == selectedYear;
                                const matchesClass = !selectedClass || option.data('class') == selectedClass;

                                if (matchesYear && matchesClass) {
                                    studentSelect.append(option.clone());
                                    matchCount++;
                                }
                            });

                            // Restore initial selected value if it still exists in the filtered list
                            if (studentSelect.find(`option[value="${initialSelectedValue}"]`).length > 0) {
                                studentSelect.val(initialSelectedValue);
                            }

                            // Re-trigger Select2 update
                            studentSelect.trigger('change');
                        }

                        $('#filter_year, #filter_class').on('change', filterStudents);

                        // Trigger initial filter
                        filterStudents();
                    });

                    function toggleProfileFields() {
                        const role = document.getElementById('role').value;
                        
                        // Hide all profile fields first
                        document.querySelectorAll('.profile-fields').forEach(el => {
                            el.style.display = 'none';
                        });

                        // Show relevant fields
                        if (role === 'parent') {
                            document.getElementById('parent-fields').style.display = 'block';
                        } else if (role === 'teacher') {
                            document.getElementById('teacher-fields').style.display = 'block';
                        } else if (role === 'mentor') {
                            document.getElementById('mentor-fields').style.display = 'block';
                        }
                    }
                </script>
            </div>
        </div>
    </div>
@endsection
