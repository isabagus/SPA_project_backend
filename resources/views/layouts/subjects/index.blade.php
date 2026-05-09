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
                                <th> Subjects </th>
                                <th> Year </th>
                                <th> Term </th>
                                <th> Detail Rubric</th>
                                <th> Actions </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($subjects as $subject)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $subject->category_subject }}</td>
                                    <td>{{ $subject->level_class }}</td>
                                    <td>{{ $subject->term }}</td>
                                    <td class="text-center align-middle">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('admin.subjects.show', $subject->subject_id) }}"
                                                class="btn btn-primary text-white">Detail</a>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('admin.subjects.edit', $subject->subject_id) }}"
                                                class="btn btn-warning text-white">Edit</a>
                                            <form action="{{ route('admin.subjects.destroy', $subject->subject_id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Delete this subject {{ $subject->name_subject }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger text-white">Delete</button>
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
