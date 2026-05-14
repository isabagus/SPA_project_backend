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
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <p class="mb-0">{{ $error }}</p>
                        @endforeach
                    </div>
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
                                    <td>{{ $subject->class->level_name }}</td>
                                    <td>{{ $subject->term }}</td>
                                    <td class="text-center align-middle">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('admin.subjects.show', $subject->subject_id) }}"
                                                class="btn btn-primary text-white">Detail</a>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            @if ($subject->report_group_key)
                                                {{-- Grouped RS/PKN: Tombol Assign Guru --}}
                                                <a href="{{ route('admin.subjects.assignTeachers', $subject->subject_id) }}"
                                                    class="btn btn-info text-white" title="Assign Guru Agama & PKN">
                                                    <i class="fa fa-users"></i> Assign Guru
                                                </a>
                                            @else
                                                {{-- Standard: Tombol Edit biasa --}}
                                                <a href="{{ route('admin.subjects.edit', $subject->subject_id) }}"
                                                    class="btn btn-warning text-white">Edit</a>
                                            @endif

                                            <form action="{{ route('admin.subjects.destroy', $subject->subject_id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Delete this subject {{ $subject->category_subject }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger text-white">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">No data available</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                <div class="mt-4 d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Showing {{ $subjects->firstItem() }} to {{ $subjects->lastItem() }} of {{ $subjects->total() }} subjects
                    </div>
                    <div>
                        {{ $subjects->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
