@extends('base')
@section('content')
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">List Subjects</h4>
                <p class="card-description"> Add Subject:
                    <a href="{{ route('admin.subjects.create') }}">Form input</a>
                </p>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped text-center">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th> Name Mentor</th>
                                <th> Email </th>
                                <th>Phone Number</th>
                                <th> Action </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($mentors as $mentor)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $mentor->name }}</td>
                                    <td>{{ $mentor->emailMentor ?? '-' }}</td>
                                    <td>{{ $mentor->phone_number }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('admin.mentors.edit', $mentor->mentor_id) }}"
                                                class="btn btn-warning btn-sm text-white">Edit</a>
                                            <form action="{{ route('admin.mentors.destroy', $mentor->mentor_id) }}"
                                                method="POST" onsubmit="return confirm('Delete this mentor?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-danger btn-sm text-white">Delete</button>
                                            </form>
                                        </div>
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
