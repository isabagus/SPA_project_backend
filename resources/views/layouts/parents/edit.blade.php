@extends('base')
@section('content')
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Edit Parent Profile</h4>
                <p class="card-description"> Update Parent Data </p>
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form class="forms-sample" method="POST" action="{{ route('admin.parents.update', $parent->parent_id) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label for="user_id">Select User Account</label>
                        <select name="user_id" class="form-control" id="user_id" required>
                            <option value="">-- Select User --</option>
                            @foreach ($usersParent as $user)
                                <option value="{{ $user->user_id }}" {{ (old('user_id', $parent->user_id)) == $user->user_id ? 'selected' : '' }}>
                                    {{ $user->username }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="student_id">Select Student (Child)</label>
                        <select name="student_id" class="form-control select2" id="student_id" required style="width: 100%;">
                            <option value="">-- Select Student --</option>
                            @foreach ($students as $student)
                                <option value="{{ $student->student_id }}" {{ (old('student_id', $parent->student_id)) == $student->student_id ? 'selected' : '' }}>
                                    {{ $student->name_student }} ({{ $student->level_class }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="name_parent">Parent Name</label>
                        <input type="text" name="name_parent" class="form-control" id="name_parent" placeholder="Full Name" value="{{ old('name_parent', $parent->name_parent) }}" required>
                    </div>

                    <button type="submit" class="btn btn-primary me-2">Update Parent</button>
                    <a href="{{ route('admin.parents.index') }}" class="btn btn-light">Cancel</a>
                </form>
                <script>
                    $(document).ready(function() {
                        $('.select2').select2({
                            placeholder: "-- Select Student --",
                            allowClear: true
                        });
                    });
                </script>
            </div>
        </div>
    </div>
@endsection
