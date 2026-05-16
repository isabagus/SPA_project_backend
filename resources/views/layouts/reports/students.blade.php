@extends('base')
@section('content')
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title">Students in {{ $students->first()->level_class ?? 'Class' }} ({{ $academic_year }})</h4>
                    <a href="{{ route('admin.reports.index') }}" class="text-dark text-decoration-none" title="Go to Reports">
                        <i class="mdi mdi-arrow-left"></i> Back to List
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th> Student Name </th>
                                <th> NIS </th>
                                <th> Avg Score </th>
                                <th> Actions </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($students as $st)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td> {{ $st->student->name_student ?? '-' }} </td>
                                    <td> {{ $st->student->nis ?? '-' }} </td>
                                    <td> <strong>{{ number_format($st->overall_avg, 1) }}</strong> </td>
                                    <td>
                                        <a href="{{ route('admin.reports.show', $st->report_id) }}"
                                            class="btn btn-warning text-white"> 
                                            <i class="mdi mdi-book-open-page-variant"></i> View Subjects
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No students found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
