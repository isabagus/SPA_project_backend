@extends('base')
@section('content')
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">List Students</h4>
                {{-- <p class="card-description"><a href="#" class="btn btn-success text-white btn-sm"><i class="mdi mdi-plus"></i> Add Student</a> 
                 --}}
                <p class="card-description">Add Subject:
                    <a href=" {{ route('admin.students.create') }}"> Form input</a>
                </p>
                </p>
                {{-- Form Search --}}
                <form action="{{ route('admin.students.index') }}" class="d-flex col-md-4">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search data student"
                            id="searchInput">
                        <button class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th> Name </th>
                                <th> Level Class </th>
                                <th> Religion </th>
                                <th> Gender </th>
                                <th> Year Academy </th>
                                <th>Parent</th>
                                <th> Mentor </th>
                                <th>Address</th>
                                <th> Phone Number </th>
                                <th> Actions </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            @foreach ($students as $st)
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
                                        <a href="{{route('admin.students.edit', $st->student_id)}}" class="btn btn-warning text-white"> Edit</a>
                                        <form action="{{ route('admin.students.destroy', $st->student_id) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                onclick="return confirm('Are sure delete this student data {{ $st->name_student }}?')"
                                                class="btn btn-danger text-white"><i class="fa fa-trash-alt"></i>
                                                Delete
                                            </button>
                                        </form>


                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
