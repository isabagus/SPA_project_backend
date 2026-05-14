@extends('base')
@section('content')
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Edit Student Profile</h4>
                <p class="card-description"> Update Student Data </p>
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form class="forms-sample" method="POST" action="{{ route('admin.students.update', $data->student_id) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Academic Year</label>
                            <select class="form-select" name="academic_year" required>
                                @foreach ($academic_years as $year)
                                    <option value="{{ $year->academic_year }}" {{ $data->academic_year == $year->academic_year ? 'selected' : '' }}>{{ $year->academic_year }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Level Class</label>
                            <select class="form-select" name="level_class" required>
                                @foreach ($level_classes as $class)
                                    <option value="{{ $class->level_class }}" {{ $data->level_class == $class->level_class ? 'selected' : '' }}>{{ $class->level_class }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label>NIS</label>
                            <input type="text" class="form-control" name="nis" value="{{ $data->nis }}" required>
                        </div>
                        <div class="col-md-3 form-group">
                            <label>Name Student</label>
                            <input type="text" class="form-control" name="name_student" value="{{ $data->name_student }}" required>
                        </div>
                        <div class="col-md-3 form-group">
                            <label>Gender</label>
                            <select class="form-select" name="gender" required>
                                <option value="male" {{ $data->gender == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ $data->gender == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                        </div>
                        <div class="col-md-3 form-group">
                            <label>Religion</label>
                            <select class="form-select" name="religion_name" required>
                                @foreach ($religions as $religion)
                                    <option value="{{ $religion->religion_name }}" {{ $data->religion_name == $religion->religion_name ? 'selected' : '' }}>{{ $religion->religion_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Address</label>
                        <textarea class="form-control" name="address" rows="3" required>{{ $data->address }}</textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" class="form-control" name="phone_number" value="{{ $data->phone_number }}" required>
                    </div>

                    <button type="submit" class="btn btn-primary me-2">Update Student</button>
                    <a href="{{ route('admin.students.index') }}" class="btn btn-light">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection
