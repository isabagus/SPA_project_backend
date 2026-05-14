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
                <form class="forms-sample" method="POST" action="{{ route('admin.mentors.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="user_id">Select User Account</label>
                        <select name="user_id" class="form-control" id="user_id">
                            <option value="">-- Create New Account --</option>
                            @foreach ($usersMentor as $user)
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
                        <label for="name_mentor">Name Mentor</label>
                        <input type="text" name="name_mentor" class="form-control" id="name_mentor" placeholder="Mentor Name" value="{{ old('name_mentor') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="nip">NIP</label>
                        <input type="text" name="nip" class="form-control" id="nip" placeholder="NIP" value="{{ old('nip') }}">
                    </div>
                    <div class="form-group">
                        <label for="phone_number">Phone Number</label>
                        <input type="text" name="phone_number" class="form-control" id="phone_number" placeholder="Phone Number" value="{{ old('phone_number') }}">
                    </div>

                    <button type="submit" class="btn btn-primary me-2">Submit</button>
                    <a href="{{ route('admin.mentors.index') }}" class="btn btn-light">Cancel</a>
                </form>

                <script>
                    $(document).ready(function() {
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
@endsection()
