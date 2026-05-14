@extends('base')
@section('content')
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Parent Form Input</h4>
                    <a href="{{ route('admin.parents.index') }}" class="text-dark text-decoration-none" title="Back to List">
                        <i class="mdi mdi-arrow-left"></i> Parents
                    </a>
                </div>
                <p class="card-description"> Add Parent Profile </p>
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form class="forms-sample" method="POST" action="{{ route('admin.parents.store') }}">
                    @csrf
                    
                    <div class="form-group">
                        <label for="user_id">Select User Account</label>
                        <select name="user_id" class="form-select" id="user_id">
                            <option value="">-- Create New Account --</option>
                            @foreach ($usersParent as $user)
                                <option value="{{ $user->user_id }}" {{ old('user_id') == $user->user_id ? 'selected' : '' }}>
                                    {{ $user->username }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Choose existing or leave empty to create new below.</small>
                    </div>

                    <div id="new_account_fields" class="border p-3 mb-3 rounded bg-white">
                        <h5 class="mb-3">New Account Information</h5>
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label for="username">Username Baru</label>
                                <input type="text" name="username" class="form-control" id="username" placeholder="Username" value="{{ old('username') }}">
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="email">Email Baru</label>
                                <input type="email" name="email" class="form-control" id="email" placeholder="email@example.com" value="{{ old('email') }}">
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="password">Password Baru</label>
                                <input type="password" name="password" class="form-control" id="password" placeholder="Min. 8 Chars">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="student_id">Select Student (Child)</label>
                        <select name="student_id" class="form-select select2" id="student_id" style="width: 100%;">
                            <option value="">-- Create New Student --</option>
                            @foreach ($students as $student)
                                <option value="{{ $student->student_id }}" {{ old('student_id') == $student->student_id ? 'selected' : '' }}>
                                    {{ $student->name_student }} ({{ $student->level_class }})
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Choose existing or leave empty to create new student below.</small>
                    </div>

                    <div id="new_student_fields" class="border p-3 mb-3 rounded bg-white">
                        <h5 class="mb-3">New Student Information</h5>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="name_student">Student Name</label>
                                <input type="text" name="name_student" class="form-control" id="name_student" placeholder="Full Name" value="{{ old('name_student') }}">
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="gender">Gender</label>
                                <select name="gender" class="form-select" id="gender">
                                    <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="phone_number_student">Student Phone</label>
                                <input type="text" name="phone_number" class="form-control" id="phone_number_student" placeholder="Phone Number" value="{{ old('phone_number') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="address">Address</label>
                            <textarea name="address" class="form-control" id="address" rows="2">{{ old('address') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="address">NIS</label>
                            <textarea name="nis" class="form-control" id="nis" rows="2">{{ old('nis') }}</textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-3 form-group">
                                <label for="academic_year">Academic Year</label>
                                <select name="academic_year" class="form-select" id="academic_year">
                                    @foreach($academic_years as $ay)
                                        <option value="{{ $ay->academic_year }}" {{ old('academic_year') == $ay->academic_year ? 'selected' : '' }}>{{ $ay->academic_year }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="level_class">Level Class</label>
                                <select name="class_id" class="form-select" id="level_class">
                                    <option value="">-- Select Class --</option>
                                    @foreach($level_classes as $lc)
                                        <option value="{{ $lc->class_id }}" {{ old('class_id') == $lc->class_id ? 'selected' : '' }}>{{ $lc->level_class }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="religion_name">Religion</label>
                                <select name="religion_name" class="form-select" id="religion_name">
                                    @foreach($religions as $rel)
                                        <option value="{{ $rel->religion_name }}" {{ old('religion_name') == $rel->religion_name ? 'selected' : '' }}>{{ $rel->religion_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name_parent">Parent Name</label>
                        <input type="text" name="name_parent" class="form-control" id="name_parent" placeholder="Full Name" value="{{ old('name_parent') }}" required>
                    </div>

                    <button type="submit" class="btn btn-primary me-2">Submit</button>
                    <a href="{{ route('admin.parents.index') }}" class="btn btn-light">Cancel</a>
                </form>
                <script>
                    $(document).ready(function() {
                        $('.select2').select2({
                            placeholder: "-- Select Student --",
                            allowClear: true
                        });

                        function toggleNewAccountFields() {
                            if ($('#user_id').val() === '') {
                                $('#new_account_fields').show();
                                $('#username, #email, #password').attr('required', true);
                            } else {
                                $('#new_account_fields').hide();
                                $('#username, #email, #password').removeAttr('required');
                            }
                        }

                        function toggleNewStudentFields() {
                            if ($('#student_id').val() === '') {
                                $('#new_student_fields').show();
                                $('#name_student, #gender, #address, #phone_number_student, #academic_year, #class_id, #religion_name').attr('required', true);
                            } else {
                                $('#new_student_fields').hide();
                                $('#name_student, #gender, #address, #phone_number_student, #academic_year, #class_id, #religion_name').removeAttr('required');
                            }
                        }

                        $('#user_id').on('change', function() {
                            toggleNewAccountFields();
                        });

                        $('#student_id').on('change', function() {
                            toggleNewStudentFields();
                        });

                        toggleNewAccountFields();
                        toggleNewStudentFields();
                    });
                </script>
            </div>
        </div>
    </div>
@endsection
