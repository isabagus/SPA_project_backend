@extends('base')
@section('content')
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title">List Teachers</h4>
                    <a href="{{ route('admin.dashboard.index') }}" class="text-dark text-decoration-none" title="Go to Dashboard">
                        <i class="mdi mdi-arrow-left"></i> Dashboard
                    </a>
                </div>

                <p class="card-description">Add Teacher:
                    <a href="{{ route('admin.teachers.create') }}"> Form input</a>
                </p>

                {{-- Form Search --}}
                <form action="{{ route('admin.teachers.index') }}" class="d-flex col-md-4">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search data teacher"
                            id="searchInput" value="{{ request('search') }}">
                        <button class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                    </div>
                </form>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped text-center">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th> Name </th>
                                <th> Email </th>
                                <th> Phone Number </th>
                                <th> Actions </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($teachers as $teacher)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td> {{ $teacher->name }} </td>
                                    <td> {{ $teacher->user->email ?? '-' }} </td>
                                    <td> {{ $teacher->phone_number }} </td>
                                    <td class="text-center align-middle">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('admin.teachers.edit', $teacher->teacher_id) }}"
                                                class="btn btn-warning text-white"> Edit</a>
                                            <form action="{{ route('admin.teachers.destroy', $teacher->teacher_id) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this teacher {{ $teacher->name }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger text-white">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">No teachers available</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $teachers->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
