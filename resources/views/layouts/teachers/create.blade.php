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
                        <div class="col-md-6 form-group">
                            <label for="user_id">User Akun</label>
                            <select class="form-select" name="user_id" id="user_id" required>
                                <option value="">Pilih User</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->user_id }}">{{ $user->username }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6 form-group">
                            <label for="name">Nama Guru</label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="Nama Guru" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="phone_number">Nomor Telepon</label>
                            <input type="text" class="form-control" name="phone_number" id="phone_number" placeholder="Nomor Telepon" required>
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

@endsection
