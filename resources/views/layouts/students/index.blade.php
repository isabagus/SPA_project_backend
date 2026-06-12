@extends('base')
@section('content')
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title">List Students</h4>
                    <a href="{{ route('admin.dashboard.index') }}" class="text-dark text-decoration-none" title="Go to Dashboard">
                        <i class="mdi mdi-arrow-left"></i> Dashboard
                    </a>
                </div>
                {{-- <p class="card-description"><a href="#" class="btn btn-success text-white btn-sm"><i class="mdi mdi-plus"></i> Add Student</a> 
                 --}}
                <p class="card-description">Add Student:
                    <a href=" {{ route('admin.students.create') }}"> Register Student</a>
                </p>
                {{-- Form Search --}}
                <form action="{{ route('admin.students.index') }}" class="d-flex col-12 col-md-4 mb-3">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search student name..."
                            id="searchInput" value="{{ request('search') }}">
                        <button class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-striped text-nowrap">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th> Name </th>
                                <th> Class Level </th>
                                <th> Religion </th>
                                <th> Gender </th>
                                <th> Academic Year </th>
                                <th>Parent</th>
                                <th> Mentor </th>
                                <th>Address</th>
                                <th> Phone Number </th>
                                <th> Actions </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            @forelse ($students as $st)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td> {{ $st->name_student }} </td>
                                    <td> {{ $st->level_class }} </td>
                                    <td> {{ $st->religion_name }} </td>
                                    <td> {{ $st->gender }}</td>
                                    <td> {{ $st->academic_year }} </td>
                                    <td> -- </td>
                                    <td> {{ $st->mentor ? $st->mentor->name_mentor : '-' }} </td>
                                    <td> {{ $st->address }} </td>
                                    <td> {{ $st->phone_number }} </td>
                                    <td>
                                        <a href="{{ route('admin.students.edit', $st->student_id) }}"
                                            class="btn btn-warning text-white"> Edit</a>
                                        <form action="{{ route('admin.students.destroy', $st->student_id) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                onclick="return confirm('Are you sure you want to delete student {{ $st->name_student }}?')"
                                                class="btn btn-danger text-white"><i class="fa fa-trash-alt"></i>
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11">No data available</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $students->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
