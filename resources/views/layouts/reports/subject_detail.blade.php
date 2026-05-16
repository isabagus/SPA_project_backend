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

                {{-- Tabel Penilaian (Rubrik) --}}
                <div class="assessment-section mb-4">
                    <table class="table table-bordered">
                        <thead class="bg-light">
                            <tr>
                                <th class="py-3 uppercase">Assessment Criteria</th>
                                <th class="py-3 text-center uppercase" width="120">Level</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($report->reportDetails as $detail)
                                <tr>
                                    <td class="py-4 px-4">
                                        <h6 class="font-weight-bold mb-2">{{ $detail->criteria->criteria_name ?? 'Criteria' }}</h6>
                                        <p class="text-justify mb-0" style="line-height: 1.6; color: #333;">
                                            {{ $detail->criteria->description ?? 'No description available' }}
                                        </p>
                                    </td>
                                    <td class="text-center align-middle">
                                        <h4 class="mb-0 font-weight-bold">{{ number_format($detail->score, 2) }}</h4>
                                    </td>
                                </tr>
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
                            <p class="border p-3 font-italic" style="min-height: 80px; border-radius: 5px;">
                                "{{ $report->mentor_note ?? 'Good progress shown.' }}"
                            </p>
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
                <div class="mt-5 d-flex justify-content-end no-print">
                    <button onclick="history.back()" class="btn btn-secondary mr-2">
                        <i class="mdi mdi-arrow-left"></i> Back
                    </button>
                    <button onclick="window.print()" class="btn btn-primary">
                        <i class="mdi mdi-printer"></i> Print to PDF
                    </button>
                </div>
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
