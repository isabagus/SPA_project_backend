@extends('base')
@section('content')
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Create Teacher</h4>
                <p class="card-description">Form Input Guru</p>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form class="forms-sample" method="POST" action="{{ route('admin.teachers.store') }}">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-12 form-group">
                            <label for="user_id">User Akun</label>
                            <select class="form-select" name="user_id" id="user_id">
                                <option value="">-- Buat Akun Baru --</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->user_id }}" {{ old('user_id') == $user->user_id ? 'selected' : '' }}>
                                        {{ $user->username }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Pilih akun yang sudah ada atau biarkan kosong untuk membuat akun baru di bawah.</small>
                        </div>
                    </div>

                    <div id="new_account_fields" class="border p-3 mb-3 rounded bg-white">
                        <h5 class="mb-3">New Account Information</h5>
                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label for="username">Username Baru</label>
                                <input type="text" class="form-control" name="username" id="username" placeholder="Username" value="{{ old('username') }}">
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="email">Email Baru</label>
                                <input type="email" class="form-control" name="email" id="email" placeholder="email@example.com" value="{{ old('email') }}">
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="password">Password Baru</label>
                                <input type="password" class="form-control" name="password" id="password" placeholder="Min. 8 Karakter">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6 form-group">
                            <label for="name">Nama Guru</label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="Nama Guru"
                                required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="phone_number">Nomor Telepon</label>
                            <input type="text" class="form-control" name="phone_number" id="phone_number"
                                placeholder="Nomor Telepon" required>
                        </div>
                    </div>
                    {{-- Jika ingin menambah relasi ke subject, bisa tambahkan di sini --}}
                    {{--
                    <div class="row mb-3">
                        <div class="col-md-12 form-group">
                            <label for="subjects">Mata Pelajaran Diampu</label>
                            <select class="form-select" name="subjects[]" id="subjects" multiple>
                                @foreach ($subjects as $subject)
                                    <option value="{{ $subject->subject_id }}">{{ $subject->subject_name }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Tekan Ctrl (Windows) / Command (Mac) untuk memilih lebih dari satu</small>
                        </div>
                    </div>
                    --}}
                    <button type="submit" class="btn btn-primary me-2">Simpan</button>
                    <a href="{{ route('admin.teachers.index') }}" class="btn btn-light">Batal</a>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

            // Run on load
            toggleNewAccountFields();
        });
    </script>
@endsection
