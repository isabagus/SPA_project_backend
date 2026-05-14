@extends('base')
@section('content')
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Set Mentor Class</h4>
                    <a href="{{ route('admin.mentors.index') }}" class="text-dark text-decoration-none" title="Back to List">
                        <i class="mdi mdi-arrow-left"></i> Mentors
                    </a>
                </div>
                <p class="card-description"> Set Mentor to a Class </p>
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form class="forms-sample" method="POST" action="{{ route('admin.mentors.updateSetClass') }}">
                    @csrf
                    @method('PUT')

                    
                    <div class="form-group">
                        <label for="mentor_id">Select Mentor</label>
                        <select name="mentor_id" class="form-control" id="mentor_id" required>
                            <option value="">-- Select Mentor --</option>
                            @foreach ($mentors as $mentor)
                                <option value="{{ $mentor->mentor_id }}" {{ (old('mentor_id') ?? $mentor->mentor_id) == $mentor->mentor_id ? 'selected' : '' }}>
                                    {{ $mentor->name_mentor }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="class_id">Select Class</label>
                        <select name="class_id" class="form-control" id="class_id" required>
                            <option value="">-- Select Class --</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->class_id }}" {{ (old('class_id') ?? '') == $class->class_id ? 'selected' : '' }}>
                                    {{ $class->level_class }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary me-2">Update Mentor</button>
                    <a href="{{ route('admin.mentors.index') }}" class="btn btn-light">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection
