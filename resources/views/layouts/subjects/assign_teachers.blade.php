@extends('base')
@section('content')
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">
                    <i class="fa fa-users me-2"></i>
                    Assign Teachers — Affective Domain RS & PKN
                </h4>
                <p class="card-description">
                    Assign homeroom teachers for each subject in the group 
                    <strong>{{ $subject->level_class }} / {{ $subject->term }}</strong>
                </p>

                @if (session('info'))
                    <div class="alert alert-info">{{ session('info') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <p class="mb-0">{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('admin.subjects.updateTeachers', $subject->subject_id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="30%">Subject Name</th>
                                    <th width="25%">Current Teacher</th>
                                    <th width="40%">Select Homeroom Teacher</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($groupSubjects as $gs)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <strong>{{ $gs->category_subject }}</strong>
                                            @if ($gs->rubrics->first())
                                                <br>
                                                <small class="text-muted">
                                                    Rubric: {{ $gs->rubrics->first()->rubric_name }}
                                                </small>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($gs->teacher_id && $gs->rubrics->first()?->teacher)
                                                <span class="badge bg-success">
                                                    {{ $gs->rubrics->first()->teacher->name }}
                                                </span>
                                            @else
                                                <span class="badge bg-warning text-dark">Not Assigned</span>
                                            @endif
                                        </td>
                                        <td>
                                            <select name="teachers[{{ $gs->subject_id }}]" class="form-select">
                                                <option value="">-- Select Teacher --</option>
                                                @foreach ($teachers as $teacher)
                                                    <option value="{{ $teacher->teacher_id }}"
                                                        {{ $gs->teacher_id == $teacher->teacher_id ? 'selected' : '' }}>
                                                        {{ $teacher->name }} ({{ $teacher->subjects->pluck('category_subject')->unique()->implode(', ') }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('admin.subjects.index') }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left me-1"></i> Back
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save me-1"></i> Save Teacher Assignments
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
