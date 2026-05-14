@extends('base')

@section('content')
<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-0">Subject Detail</h3>
                    <p class="text-muted mb-0">Informasi lengkap kriteria penilaian subject</p>
                </div>
                <a href="{{ route('admin.subjects.index') }}" class="btn btn-light border">
                    <i class="fa fa-arrow-left me-1"></i> Kembali
                </a>
            </div>

            {{-- Header Info --}}
            <div class="row mb-4">
                <div class="col-md-4">
                    <label class="text-muted small fw-bold">Subject Category</label>
                    <h5 class="fw-bold">{{ $subject->category_subject }}</h5>
                </div>
                <div class="col-md-4">
                    <label class="text-muted small fw-bold">Year / Class</label>
                    <h5 class="fw-bold">{{ $subject->level_class }}</h5>
                </div>
                <div class="col-md-4">
                    <label class="text-muted small fw-bold">Term</label>
                    <h5 class="fw-bold text-primary">{{ $subject->term }}</h5>
                </div>
            </div>

            <hr class="my-4">

            {{-- Rubrics Table --}}
            <h5 class="fw-bold mb-3">Assessment Rubrics (Item Penilaian)</h5>
            <div class="table-responsive">
                <table class="table table-striped text-center">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th>Rubric namespace</th>
                            <th>Teacher In Charge</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($subject->rubrics as $rubric)
                            <tr class="bg-light">
                                <td>{{ $loop->iteration }}</td>
                                <td class="fw-bold text-primary">{{ $rubric->rubric_name }}</td>
                                <td>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px; font-size: 12px;">
                                            {{ strtoupper(substr($rubric->teacher->name ?? 'T', 0, 1)) }}
                                        </div>
                                        <span>{{ $rubric->teacher->name ?? '-' }}</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="p-0">
                                    <div class="px-5 py-2 bg-white">
                                        <ul class="list-group list-group-flush text-start">
                                            @foreach($rubric->criteria as $criteria)
                                                <li class="list-group-item d-flex align-items-center py-2 border-0">
                                                    <i class="fa fa-caret-right text-muted me-2"></i>
                                                    <span class="small">{{ $criteria->criteria_name }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">Belum ada rubrik penilaian untuk subject ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 pt-3 border-top d-flex gap-2">
                <a href="{{ route('admin.subjects.edit', $subject->subject_id) }}" class="btn btn-warning text-white px-4">
                    <i class="fa fa-edit me-1"></i> Edit Data
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.table thead th { font-weight: bold; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; }
.card { border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
</style>
@endsection
