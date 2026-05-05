@extends('base')
@section('content')
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">List Students</h4>
                {{-- <p class="card-description"><a href="#" class="btn btn-success text-white btn-sm"><i class="mdi mdi-plus"></i> Add Student</a> 
                 --}}
                <p class="card-description">Add Teacher:
                    <a href=" {{ route('admin.teachers.create') }}"> Form input</a>
                </p>
                </p>
                {{-- Form Search --}}
                <form action="{{ route('admin.teachers.search') }}" class="d-flex col-md-4">
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
                                <th>Phone Number</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            @forelse ($teachers as $st)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td> {{ $st->name }} </td>
                                    <td> {{ $st->phone_number }} </td>
                                    <td>
                                        <a href="{{ route('admin.teachers.edit', $st->teacher_id) }}"
                                            class="btn btn-primary text-white"> Edit</a>
                                        {{-- <button type="button" class="btn btn-primary text-white">Edit</button> --}}
                                        <form action="{{ route('admin.teachers.destroy', $st->teacher_id) }}" method="POST"
                                            class="d-inline">
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
                                @empty
                                <tr>
                                    <td colspan="5">No data available</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
