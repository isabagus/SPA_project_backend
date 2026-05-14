@extends('base')
@section('content')
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title">List Parents</h4>
                    <a href="{{ route('admin.dashboard.index') }}" class="text-dark text-decoration-none" title="Go to Dashboard">
                        <i class="mdi mdi-arrow-left"></i> Dashboard
                    </a>
                </div>

                <p class="card-description">Add Parent:
                    <a href="{{ route('admin.parents.create') }}"> Form input</a>
                </p>

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
                                <th>Parent Name</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Student Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($parents as $parent)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td> {{ $parent->name_parent }} </td>
                                    <td> {{ $parent->user->username ?? '-' }} </td>
                                    <td> {{ $parent->user->email ?? '-' }} </td>
                                    <td> {{ $parent->student->name_student }} </td>
                                    <td class="text-center align-middle">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('admin.parents.edit', $parent->parent_id) }}"
                                                class="btn btn-warning text-white"> Edit</a>
                                            <form action="{{ route('admin.parents.destroy', $parent->parent_id) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this parent data ? {{ $parent?->name_parent }}?')">
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
                                    <td colspan="6">No parents available</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{$parents->links()}}
                </div>
            </div>
        </div>
    </div>
@endsection
