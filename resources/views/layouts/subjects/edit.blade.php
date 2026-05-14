@extends('base')

@section('content')
<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Edit Subject & Rubrics</h4>
            <p class="card-description text-muted">Update Header Info and Assessment Items</p>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.subjects.update', $subject->subject_id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label class="fw-bold mb-2">Subject</label>
                        <select class="form-select" name="category_subject" required>
                            @foreach ($subjectCategories as $cat)
                                <option value="{{ $cat->category_subject }}" {{ $subject->category_subject == $cat->category_subject ? 'selected' : '' }}>
                                    {{ $cat->category_subject }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="fw-bold mb-2">Term</label>
                        <select class="form-select" name="term" required>
                            @foreach ($terms as $t)
                                <option value="{{ $t->term }}" {{ $subject->term == $t->term ? 'selected' : '' }}>
                                    {{ $t->term }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label class="fw-bold mb-2">Year / Class</label>
                    <select name="level_class" class="form-select" required>
                        @foreach($years as $year)
                            <option value="{{ $year->class_id }}" {{ $subject->level_class == $year->class_id ? 'selected' : '' }}>
                                {{ $year->level_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold">Rubric Criteria (Sub-Subjects)</h5>
                    <button type="button" class="btn btn-primary btn-sm" id="add-rubric">
                        <i class="fa fa-plus me-1"></i> Tambah Item Baru
                    </button>
                </div>

                <div id="rubrics-container">
                    @foreach ($subject->rubrics as $index => $rubric)
                        <div class="card border mb-4 rubric-item shadow-sm">
                            <div class="card-body bg-white">
                                {{-- Input hidden untuk rubric_id agar sistem tahu ini data lama --}}
                                <input type="hidden" name="rubrics[{{ $index }}][rubric_id]" value="{{ $rubric->rubric_id }}">
                                
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge bg-secondary rubric-number">Rubric {{ $index + 1 }}</span>
                                    <button type="button" class="btn btn-danger btn-sm remove-rubric">Hapus Rubrik</button>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-8 form-group">
                                        <label class="small fw-bold mb-2">Category Name / Judul Rubrik</label>
                                        <input type="text" name="rubrics[{{ $index }}][name]" value="{{ $rubric->rubric_name }}" class="form-control" required>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label class="small fw-bold mb-2">Teacher</label>
                                        <select name="rubrics[{{ $index }}][teacher_id]" class="form-select" required>
                                            @foreach($teachers as $teacher)
                                                <option value="{{ $teacher->teacher_id }}" {{ $rubric->teacher_id == $teacher->teacher_id ? 'selected' : '' }}>
                                                    {{ $teacher->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="ms-4 p-3 border-start border-4 border-primary bg-light rounded">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="fw-bold mb-0 text-primary uppercase text-xs tracking-widest">
                                            <i class="fa fa-list-ul me-1"></i> List Kriteria (Sub-Items)
                                        </h6>
                                        <button type="button" class="btn btn-outline-primary btn-xs add-criteria" data-rubric-index="{{ $index }}">
                                            <i class="fa fa-plus me-1"></i> Tambah Kriteria
                                        </button>
                                    </div>
                                    <div class="criteria-container" data-rubric-index="{{ $index }}">
                                        @foreach($rubric->criteria as $cIndex => $criteria)
                                            <div class="criteria-item mb-2 d-flex gap-2">
                                                <input type="hidden" name="rubrics[{{ $index }}][criteria][{{ $cIndex }}][criteria_id]" value="{{ $criteria->criteria_id }}">
                                                <input type="text" name="rubrics[{{ $index }}][criteria][{{ $cIndex }}][name]" value="{{ $criteria->criteria_name }}" class="form-control form-control-sm" required>
                                                <button type="button" class="btn btn-outline-danger btn-sm remove-criteria"><i class="fa fa-times"></i></button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 pt-3 border-top">
                    <button type="submit" class="btn btn-success me-2 px-4 py-2 fw-bold">Update Semua Data</button>
                    <a href="{{ route('admin.subjects.index') }}" class="btn btn-light border px-4 py-2">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .btn-xs { padding: 0.25rem 0.5rem; font-size: 0.75rem; }
    .rubric-item { border-radius: 1.5rem !important; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('rubrics-container');
    const addButton = document.getElementById('add-rubric');
    let rubricCount = document.querySelectorAll('.rubric-item').length;

    // Tambah Rubrik
    addButton.addEventListener('click', function() {
        const index = rubricCount;
        rubricCount++;
        
        const newRubric = document.createElement('div');
        newRubric.className = 'card border mb-4 rubric-item shadow-sm';
        newRubric.innerHTML = `
            <div class="card-body bg-white">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="badge bg-secondary rubric-number">Rubric ${rubricCount}</span>
                    <button type="button" class="btn btn-danger btn-sm remove-rubric">Hapus Rubrik</button>
                </div>
                <div class="row mb-4">
                    <div class="col-md-8 form-group">
                        <label class="small fw-bold mb-2">Category Name / Judul Rubrik</label>
                        <input type="text" name="rubrics[${index}][name]" class="form-control" placeholder="Contoh: Category Baru" required>
                    </div>
                    <div class="col-md-4 form-group">
                        <label class="small fw-bold mb-2">Teacher</label>
                        <select name="rubrics[${index}][teacher_id]" class="form-select" required>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->teacher_id }}">{{ $teacher->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="ms-4 p-3 border-start border-4 border-primary bg-light rounded">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0 text-primary uppercase text-xs tracking-widest">
                            <i class="fa fa-list-ul me-1"></i> List Kriteria (Sub-Items)
                        </h6>
                        <button type="button" class="btn btn-outline-primary btn-xs add-criteria" data-rubric-index="${index}">
                            <i class="fa fa-plus me-1"></i> Tambah Kriteria
                        </button>
                    </div>
                    <div class="criteria-container" data-rubric-index="${index}">
                        <div class="criteria-item mb-2 d-flex gap-2">
                            <input type="text" name="rubrics[${index}][criteria][0][name]" class="form-control form-control-sm" placeholder="Contoh: Kriteria Baru" required>
                            <button type="button" class="btn btn-outline-danger btn-sm remove-criteria"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                </div>
            </div>`;
        container.appendChild(newRubric);
    });

    // Delegasi Event
    container.addEventListener('click', function(e) {
        // Hapus Rubrik
        if (e.target.classList.contains('remove-rubric')) {
            const items = document.querySelectorAll('.rubric-item');
            if (items.length > 1) {
                e.target.closest('.rubric-item').remove();
                updateRubricNumbers();
            } else {
                alert('Minimal harus ada satu kategori rubrik.');
            }
        }

        // Tambah Kriteria
        if (e.target.closest('.add-criteria')) {
            const btn = e.target.closest('.add-criteria');
            const rubricIndex = btn.getAttribute('data-rubric-index');
            const criteriaContainer = btn.closest('.ms-4').querySelector('.criteria-container');
            const criteriaCount = criteriaContainer.querySelectorAll('.criteria-item').length;
            
            const newCriteria = document.createElement('div');
            newCriteria.className = 'criteria-item mb-2 d-flex gap-2';
            newCriteria.innerHTML = `
                <input type="text" name="rubrics[${rubricIndex}][criteria][${criteriaCount}][name]" class="form-control form-control-sm" placeholder="Kriteria Selanjutnya..." required>
                <button type="button" class="btn btn-outline-danger btn-sm remove-criteria"><i class="fa fa-times"></i></button>
            `;
            criteriaContainer.appendChild(newCriteria);
        }

        // Hapus Kriteria
        if (e.target.closest('.remove-criteria')) {
            const item = e.target.closest('.criteria-item');
            const container = item.closest('.criteria-container');
            if (container.querySelectorAll('.criteria-item').length > 1) {
                item.remove();
                reindexCriteria(container);
            } else {
                alert('Minimal satu kriteria per rubrik.');
            }
        }
    });

    function updateRubricNumbers() {
        document.querySelectorAll('.rubric-item').forEach((el, i) => {
            el.querySelector('.rubric-number').textContent = `Rubric ${i + 1}`;
            const rubricIdInput = el.querySelector('input[name*="[rubric_id]"]');
            const catInput = el.querySelector('input[name*="[name]"]');
            const teacherSelect = el.querySelector('select[name*="[teacher_id]"]');
            const criteriaBtn = el.querySelector('.add-criteria');
            const criteriaContainer = el.querySelector('.criteria-container');

            if (rubricIdInput) rubricIdInput.name = `rubrics[${i}][rubric_id]`;
            catInput.name = `rubrics[${i}][name]`;
            teacherSelect.name = `rubrics[${i}][teacher_id]`;
            criteriaBtn.setAttribute('data-rubric-index', i);
            criteriaContainer.setAttribute('data-rubric-index', i);
            
            reindexCriteria(criteriaContainer);
        });
    }

    function reindexCriteria(criteriaContainer) {
        const rubricIndex = criteriaContainer.getAttribute('data-rubric-index');
        criteriaContainer.querySelectorAll('.criteria-item').forEach((item, j) => {
            const idInput = item.querySelector('input[name*="[criteria_id]"]');
            const nameInput = item.querySelector('input[name*="[name]"]');
            
            if (idInput) idInput.name = `rubrics[${rubricIndex}][criteria][${j}][criteria_id]`;
            nameInput.name = `rubrics[${rubricIndex}][criteria][${j}][name]`;
        });
    }
});
</script>
@endsection
