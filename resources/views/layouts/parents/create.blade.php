@extends('base')
@section('content')
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Parent Form Input</h4>
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

                    <div id="new_account_fields">
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
                        <select name="student_id" class="form-select select2" id="student_id" required style="width: 100%;">
                            <option value="">-- Select Student --</option>
                            @foreach ($students as $student)
                                <option value="{{ $student->student_id }}" {{ old('student_id') == $student->student_id ? 'selected' : '' }}>
                                    {{ $student->name_student }} ({{ $student->level_class }})
                                </option>
                            @endforeach
                        </select>
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

                        $('#user_id').on('change', function() {
                            toggleNewAccountFields();
                        });

                        toggleNewAccountFields();
                    });
                </script>
            </div>
        </div>
    </div>
@endsection
