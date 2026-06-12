@extends('base')
@section('content')
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-5">
                {{-- Header Laporan --}}
                <div class="text-center mb-5">
                    <h3 class="font-weight-bold uppercase mb-1">TERM REPORT {{ $report->academic_year }}</h3>
                    <div class="d-flex justify-content-center">
                        <hr class="w-25 border-dark">
                    </div>
                </div>

                {{-- Info Siswa & Mata Pelajaran --}}
                <div class="row mb-5" style="font-size: 1.1rem;">
                    <div class="col-md-7">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td width="180" class="font-weight-bold uppercase">{{ $report->subject->name_subject ?? 'SUBJECT' }}</td>
                                <td class="font-weight-bold">: {{ strtoupper($report->subject->category_subject) }}</td>
                            </tr>
                            <tr>
                                <td class="font-italic">Name of Student</td>
                                <td class="font-weight-bold">: {{ $report->student->name_student }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                {{-- Form Penilaian (CRUD mode jika request('edit') == 1) --}}
                @if(request('edit') == 1)
                <form action="{{ route('admin.reports.update_subject_detail', $report->report_id) }}" method="POST">
                    @csrf
                    @method('PUT')
                @endif

                {{-- Tabel Penilaian (Rubrik) --}}
                <div class="assessment-section mb-4">
                    <table class="table table-bordered">
                        <colgroup>
                            <col width="80%">
                            <col width="20%">
                        </colgroup>
                        <thead class="bg-light">
                            <tr>
                                <th class="py-3 uppercase">Assessment Criteria</th>
                                <th class="py-3 text-center uppercase">Level</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $groupedDetails = $report->reportDetails->groupBy(function($detail) {
                                    return $detail->rubric_id ?? ($detail->criteria->rubric_id ?? 0);
                                });
                            @endphp

                            @forelse ($groupedDetails as $rubricId => $details)
                                @php
                                    $firstDetail = $details->first();
                                    $rubric = $firstDetail->rubric ?? ($firstDetail->criteria->category ?? null);
                                    $rubricName = $rubric->rubric_name ?? 'General Rubric';
                                @endphp
                                <tr class="bg-light">
                                    <td colspan="2" class="py-3 px-4">
                                        <h5 class="font-weight-bold text-uppercase mb-0" style="font-style: italic; color: #1f2937;">
                                            {{ $rubricName }}
                                        </h5>
                                    </td>
                                </tr>
                                @foreach ($details as $detail)
                                    <tr>
                                        <td class="py-4 px-4">
                                            <h6 class="font-weight-bold mb-2">{{ $detail->criteria->criteria_name ?? 'Criteria' }}</h6>
                                            @if(request('edit') == 1)
                                                <div class="form-group mt-2 mb-0">
                                                    <label class="small font-weight-bold text-muted">Assessment Comment / Subject Description:</label>
                                                    <textarea name="descriptions[{{ $detail->id }}]" class="form-control" rows="3" style="line-height: 1.5;">{{ $detail->description_subject ?: ($detail->criteria->default_description ?? '') }}</textarea>
                                                </div>
                                            @else
                                                <p class="text-justify mb-0" style="line-height: 1.6; color: #333;">
                                                    {{ $detail->description_subject ?: ($detail->criteria->default_description ?? 'No description available') }}
                                                </p>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">
                                            @if(request('edit') == 1)
                                                <input type="number" name="scores[{{ $detail->id }}]" class="form-control text-center font-weight-bold mx-auto" style="width: 100px; font-size: 1.2rem;" step="0.01" min="1.00" max="3.00" value="{{ $detail->score }}" required>
                                            @else
                                                <h4 class="mb-0 font-weight-bold">{{ number_format($detail->score, 2) }}</h4>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center py-5">No detailed assessment available</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="bg-light">
                            <tr>
                                <th class="py-3 font-weight-bold uppercase">AVERAGE</th>
                                <th class="py-3 text-center">
                                    <h4 class="mb-0 font-weight-bold">{{ number_format($report->average_value, 2) }}</h4>
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- Footer: Legend & Signature --}}
                <div class="row mt-5">
                    <div class="col-md-6">
                        <div class="p-3 border" style="background-color: #f9f9f9; border-radius: 5px;">
                            <h6 class="font-weight-bold small mb-2 uppercase">Level Guide:</h6>
                            <ul class="list-unstyled small mb-0">
                                <li>[1.00 - 1.99] <em>Improving</em></li>
                                <li>[2.00 - 2.49] <em>Meeting expectations</em></li>
                                <li>[2.50 - 3.00] <em>Exceeding expectations</em></li>
                            </ul>
                        </div>
                        
                        <div class="mt-4">
                            <h6 class="font-weight-bold uppercase mb-2">Mentor Feedback:</h6>
                            @if(request('edit') == 1)
                                <textarea name="mentor_note" class="form-control font-italic" style="min-height: 100px; border-radius: 5px; line-height: 1.5;" placeholder="Good progress shown.">{{ $report->mentor_note }}</textarea>
                            @else
                                <p class="border p-3 font-italic" style="min-height: 80px; border-radius: 5px; background-color: #ffffff;">
                                    "{{ $report->mentor_note ?? 'Good progress shown.' }}"
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6 d-flex flex-column align-items-center justify-content-end pb-3">
                        <p class="mb-5 uppercase font-weight-bold">Teacher:</p>
                        <p class="mb-0 font-weight-bold border-bottom border-dark px-5">
                            {{ $report->subject->teacher->name ?? 's' }}
                        </p>
                    </div>
                </div>

                {{-- Action Buttons (Hide when Printing) --}}
                <div class="mt-5 d-flex justify-content-end no-print" style="gap: 12px;">
                    @if(request('edit') == 1)
                        <a href="{{ route('admin.reports.subject_detail', $report->report_id) }}" class="btn btn-secondary">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="mdi mdi-content-save"></i> Save Changes
                        </button>
                    @else
                        <button onclick="history.back()" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left"></i> Back
                        </button>
                        <a href="{{ route('admin.reports.subject_detail', $report->report_id) }}?edit=1" class="btn btn-warning text-white">
                            <i class="mdi mdi-pencil"></i> Edit Scores & Comments
                        </a>
                        <a href="{{ route('admin.reports.print', $report->report_id) }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary">
                            <i class="mdi mdi-printer"></i> Print Report
                        </a>
                        <a href="{{ route('admin.reports.export', $report->report_id) }}" class="btn btn-success">
                            <i class="mdi mdi-download"></i> Export PDF
                        </a>
                    @endif
                </div>

                @if(request('edit') == 1)
                </form>
                @endif
            </div>
        </div>
    </div>

    <style>
        @media print {
            .no-print { display: none !important; }
            .card { border: none !important; box-shadow: none !important; }
            body { background: white !important; }
            .content-wrapper { padding: 0 !important; }
        }
        .uppercase { text-transform: uppercase; }
    </style>
@endsection
