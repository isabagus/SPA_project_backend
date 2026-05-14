@extends('base')
@section('content')
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Edit Teacher</h4>
                    <a href="{{ route('admin.teachers.index') }}" class="text-dark text-decoration-none" title="Back to List">
                        <i class="mdi mdi-arrow-left"></i> Teachers
                    </a>
                </div>
                <p class="card-description">Form Update Guru</p>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form class="forms-sample" method="POST" action="{{ route('admin.teachers.update', $teacher->teacher_id) }}">
                    @csrf
                    @method('PUT')
                    <div class="row mb-3">
                        <div class="col-md-6 form-group">
                            <label for="user_id">User Akun</label>
                            <select class="form-select" name="user_id" id="user_id" required>
                                <option value="">Pilih User</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->user_id }}" {{ (old('user_id') ?? $teacher->user_id) == $user->user_id ? 'selected' : '' }}>
                                        {{ $user->username }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6 form-group">
                            <label for="name">Nama Guru</label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="Nama Guru"
                                value="{{ old('name') ?? $teacher->name }}" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="phone_number">Nomor Telepon</label>
                            <input type="text" class="form-control" name="phone_number" id="phone_number"
                                placeholder="Nomor Telepon" value="{{ old('phone_number') ?? $teacher->phone_number }}" required>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary me-2">Update</button>
                    <a href="{{ route('admin.teachers.index') }}" class="btn btn-light">Batal</a>
                </form>
            </div>
        </div>
    </div>
@endsection
