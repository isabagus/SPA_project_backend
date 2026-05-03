@extends('base')
@section('content')
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Student Form Input</h4>
                <p class="card-description"> Add Student </p>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form class="forms-sample" method="POST" action="{{ route('admin.students.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="academic_year">Academic Year</label>
                        <select class="form-select" id="academic_year" name="academic_year" required>
                            <option value="">Select Academic Year</option>
                            @foreach ($academic_years as $year)
                                <option value="{{ $year->academic_year }}" {{ old('academic_year') == $year->academic_year ? 'selected' : '' }}>{{ $year->academic_year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="mentor_id">Mentor</label>
                        <select class="form-select" id="mentor_id" name="mentor_id" required>
                            <option value="">Select Mentor</option>
                            @foreach ($mentors as $mentor)
                                <option value="{{ $mentor->mentor_id }}" {{ old('mentor_id') == $mentor->mentor_id ? 'selected' : '' }}>{{ $mentor->name_mentor }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name_student">Name Student</label>
                        <input type="text" class="form-control" id="name_student" name="name_student" value="{{ old('name_student') }}" placeholder="Name Student" required>
                    </div>
                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <select class="form-select" id="gender" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea class="form-control" id="address" name="address" placeholder="Address" rows="4" required>{{ old('address') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="phone_number">Phone Number</label>
                        <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" placeholder="Phone Number" required>
                    </div>
                    <button type="submit" class="btn btn-primary me-2">Submit</button>
                    <a href="{{ route('admin.students.index') }}" class="btn btn-light">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection()
