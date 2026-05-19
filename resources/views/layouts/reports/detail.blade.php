@extends('base')
@section('content')
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title">Academic Report Detail</h4>
                    <a href="{{ route('admin.reports.index') }}" class="text-dark text-decoration-none" title="Go to Reports">
                        <i class="mdi mdi-arrow-left"></i> Back to List
                    </a>
                </div>

                {{-- Student Info Header --}}
                <div class="row mb-4 bg-light p-3 mx-0" style="border-radius: 10px;">
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td width="150" class="font-weight-bold">Student Name</td>
                                <td>: {{ $mainReport->student->name_student }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">NIS</td>
                                <td>: {{ $mainReport->student->nis }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td width="150" class="font-weight-bold">Class</td>
                                <td>: {{ $mainReport->level_class }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">Academic Year</td>
                                <td>: {{ $mainReport->academic_year }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th width="50">No</th>
                                <th>Subject Name</th>
                                <th class="text-center">Score (Avg)</th>
                                <th class="text-center">Attendance</th>
                                <th>Mentor Note</th>
                                <th class="text-center">Actions</th>
                            </tr>
</thead>
                        <tbody>
                            @foreach ($allSubjects as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <span class="font-weight-bold">{{ $item->subject->category_subject ?? 'N/A' }}</span>
                                    </td>
                                    <td class="text-center">
                                        <h5 class="mb-0 {{ $item->average_value >= 75 ? 'text-success' : 'text-danger' }}">
                                            {{ number_format($item->average_value, 1) }}
                                        </h5>
                                    </td>
                                    <td class="text-center">{{ $item->attendance }} Days</td>
                                    <td style="white-space: normal;">
                                        <small>{{ $item->mentor_note ?? '-' }}</small>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.reports.subject_detail', $item->report_id) }}" 
                                           class="btn btn-info btn-sm text-white">
                                           <i class="mdi mdi-format-list-bulleted"></i> View Rubrics
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-light">
                            <tr>
                                <th colspan="2" class="text-right">Overall Average Score</th>
                                <th class="text-center">
                                    <h4 class="mb-0 text-primary">{{ number_format($allSubjects->avg('average_value'), 1) }}</h4>
                                </th>
                                <th colspan="2"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="mt-4 d-flex justify-content-end">
                    <button class="btn btn-primary" onclick="window.print()">
                        <i class="mdi mdi-printer"></i> Print Report
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
