@extends('base')

@section('content')
<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="card-title">Subject Form Input</h4>
                <a href="{{ route('admin.subjects.index') }}" class="text-dark text-decoration-none" title="Back to List">
                    <i class="mdi mdi-arrow-left"></i> Subjects
                </a>
            </div>
            <p class="card-description text-muted">Add Multiple Subjects under one Category</p>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.subjects.store') }}" method="POST">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-6 form-group">
                        <label class="mb-2 fw-bold">Subject Category (Header)</label>
                        <select class="form-select" id="category_subject" name="category_subject" required>
                            <option value="">Select Subject</option>
                            @foreach ($categorySubjects as $sub)
                                <option value="{{ $sub->category_subject }}" {{ old('category_subject') == $sub->category_subject ? 'selected' : '' }}>
                                    {{ $sub->category_subject }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="mb-2 fw-bold">Term</label>
                        <select class="form-select" name="term" required>
                            <option value="">Select Term</option>
                            @foreach ($terms as $t)
                                <option value="{{ $t->term }}" {{ old('term') == $t->term ? 'selected' : '' }}>
                                    {{ $t->term }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label class="mb-2 fw-bold">Year / Class</label>
                    <select name="class_id" class="form-select" required>
                        <option value="">Select Class</option>
                        @foreach ($years as $year)
                            <option value="{{ $year->class_id }}" {{ old('class_id') == $year->class_id ? 'selected' : '' }}>
                                {{ $year->level_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <hr>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold">Rubric Criteria</h5>
                    <button type="button" class="btn btn-primary btn-sm" id="add-rubric">
                        <i class="fa fa-plus me-1"></i> Add Rubric
                    </button>
                </div>

                <div id="rubrics-container">
                    @php
                        $oldRubrics = old('rubrics', [
                            ['name' => '', 'teacher_id' => '', 'criteria' => [['name' => '']]]
                        ]);
                    @endphp

                    @foreach($oldRubrics as $rIndex => $rData)
                        <div class="card border mb-4 rubric-item shadow-sm">
                            <div class="card-body bg-white">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge bg-secondary rubric-number">Rubric {{ $rIndex + 1 }}</span>
                                    <button type="button" class="btn btn-danger btn-sm remove-rubric">Hapus Rubrik</button>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-8 form-group">
                                        <label class="small fw-bold mb-2">Category Name / Judul Rubrik</label>
                                        <input type="text" name="rubrics[{{ $rIndex }}][name]" value="{{ $rData['name'] }}" class="form-control" placeholder="Contoh: Reading & Writing" required>
                                    </div>
                                    <div class="col-md-4 form-group teacher-assign-container">
                                        <label class="small fw-bold mb-2">Assign to Teacher</label>
                                        <select name="rubrics[{{ $rIndex }}][teacher_id]" class="form-select teacher-select">
                                            <option value="">Select Teacher</option>
                                            @foreach($teachers as $teacher)
                                                <option value="{{ $teacher->teacher_id }}" {{ $rData['teacher_id'] == $teacher->teacher_id ? 'selected' : '' }}>
                                                    {{ $teacher->name }} ({{ $teacher->subjects->pluck('category_subject')->unique()->implode(', ') }})
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
                                        <button type="button" class="btn btn-outline-primary btn-xs add-criteria" data-rubric-index="{{ $rIndex }}">
                                            <i class="fa fa-plus me-1"></i> Tambah Kriteria
                                        </button>
                                    </div>
                                    <div class="criteria-container" data-rubric-index="{{ $rIndex }}">
                                        @foreach($rData['criteria'] as $cIndex => $cData)
                                            <div class="criteria-item mb-2 d-flex gap-2">
                                                <input type="text" name="rubrics[{{ $rIndex }}][criteria][{{ $cIndex }}][name]" value="{{ $cData['name'] }}" class="form-control form-control-sm" placeholder="Contoh: Item Kriteria" required>
                                                <button type="button" class="btn btn-outline-danger btn-sm remove-criteria"><i class="fa fa-times"></i></button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success me-2 px-4 py-2 fw-bold">Simpan Semua Data</button>
                    <a href="{{ route('admin.subjects.index') }}" class="btn btn-light border px-4 py-2">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .btn-xs { padding: 0.25rem 0.5rem; font-size: 0.75rem; }
    .criteria-item input::placeholder { font-style: italic; opacity: 0.6; }
    .rubric-item { border-radius: 1.5rem !important; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('rubrics-container');
    const addButton = document.getElementById('add-rubric');
    // Initialize count based on how many rubrics are already rendered (from old() or default)
    let rubricCount = document.querySelectorAll('.rubric-item').length;

    const categorySelect = document.getElementById('category_subject');

    function addRubric(initialData = null) {
        const index = document.querySelectorAll('.rubric-item').length;
        const rubricCount = index + 1;
        
        const newRubric = document.createElement('div');
        newRubric.className = 'card border mb-4 rubric-item shadow-sm';
        
        const rubricName = initialData ? initialData.name : '';
        const isGroupedRS = document.getElementById('category_subject').value === 'Affective Domain RS & PKN';
        
        // Selective Visibility: Hide only if specifically told to, or if category is RS/PKN and it's the general rubric
        let teacherDisplay = 'block';
        if (initialData && initialData.hideTeacher === true) {
            teacherDisplay = 'none';
        } else if (isGroupedRS && rubricName === 'Religious Studies / Agama') {
            teacherDisplay = 'none';
        }

        const criteriaHtml = initialData && initialData.criteria 
            ? initialData.criteria.map((c, j) => `
                <div class="criteria-item mb-2 d-flex gap-2">
                    <input type="text" name="rubrics[${index}][criteria][${j}][name]" class="form-control form-control-sm" value="${c}" required>
                    <button type="button" class="btn btn-outline-danger btn-sm remove-criteria"><i class="fa fa-times"></i></button>
                </div>`).join('')
            : `
                <div class="criteria-item mb-2 d-flex gap-2">
                    <input type="text" name="rubrics[${index}][criteria][0][name]" class="form-control form-control-sm" placeholder="Contoh: Kriteria Baru" required>
                    <button type="button" class="btn btn-outline-danger btn-sm remove-criteria"><i class="fa fa-times"></i></button>
                </div>`;

        newRubric.innerHTML = `
            <div class="card-body bg-white">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="badge bg-secondary rubric-number">Rubric ${rubricCount}</span>
                    <button type="button" class="btn btn-danger btn-sm remove-rubric">Hapus Rubrik</button>
                </div>
                <div class="row mb-4">
                    <div class="col-md-8 form-group">
                        <label class="small fw-bold mb-2">Category Name / Judul Rubrik</label>
                        <input type="text" name="rubrics[${index}][name]" class="form-control" value="${rubricName}" placeholder="Contoh: Category Baru" required>
                    </div>
                    <div class="col-md-4 form-group teacher-assign-container" style="display: ${teacherDisplay}">
                        <label class="small fw-bold mb-2">Assign to Teacher</label>
                        <select name="rubrics[${index}][teacher_id]" class="form-select teacher-select">
                            <option value="">Select Teacher</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->teacher_id }}">{{ $teacher->name }} ({{ $teacher->subjects->pluck('category_subject')->unique()->implode(', ') }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 form-group mentor-info-container" style="display: ${teacherDisplay === 'none' ? 'block' : 'none'}">
                        <label class="small fw-bold mb-2">Pengampu</label>
                        <div class="d-flex align-items-center gap-2 p-2 bg-info bg-opacity-10 border border-info rounded">
                            <i class="fa fa-info-circle text-info"></i>
                            <span class="small text-info fw-semibold">Guru agama akan ditugaskan via tombol <strong>"Assign Guru"</strong> setelah disimpan</span>
                        </div>
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
                        ${criteriaHtml}
                    </div>
                </div>
            </div>`;
        container.appendChild(newRubric);
    }

    // Tambah Rubrik
    addButton.addEventListener('click', function() {
        addRubric();
    });

    categorySelect.addEventListener('change', function() {
        const isGroupedRS = this.value === 'Affective Domain RS & PKN';
        
        // Update visibility of existing rubrics
        document.querySelectorAll('.rubric-item').forEach(rubric => {
            const nameInput = rubric.querySelector('input[name*="[name]"]');
            const teacherContainer = rubric.querySelector('.teacher-assign-container');
            const teacherSelect = rubric.querySelector('.teacher-select');
            
            if (isGroupedRS) {
                // If RS/PKN category: Hide RS, Show PKN
                if (nameInput && nameInput.value === 'Religious Studies / Agama') {
                    teacherContainer.style.display = 'none';
                    if (teacherSelect) {
                        teacherSelect.value = '';
                        teacherSelect.removeAttribute('required');
                    }
                } else {
                    teacherContainer.style.display = 'block';
                    if (teacherSelect) teacherSelect.setAttribute('required', 'required');
                }
            } else {
                // Standard categories: Show all
                teacherContainer.style.display = 'block';
                if (teacherSelect) teacherSelect.setAttribute('required', 'required');
            }
        });

        if (isGroupedRS) {
            // Confirm with user if they want to clear existing rubrics
            if (document.querySelectorAll('.rubric-item').length > 0 && 
                !confirm('Memilih kategori ini akan menghapus rubrik yang sudah Anda isi dan menggantinya dengan template otomatis. Lanjutkan?')) {
                return;
            }

            // Clear current rubrics
            container.innerHTML = '';

            // Add Religious Studies / Agama (Hidden Teacher Selection - handled by Mentor)
            addRubric({
                name: 'Religious Studies / Agama',
                hideTeacher: true,
                criteria: [
                    'Demonstrates good understanding of subject matter',
                    'Participates actively in lessons'
                ]
            });

            // Add PKN (Visible Teacher Selection - handled by PKN Teacher)
            addRubric({
                name: 'PKN',
                hideTeacher: false,
                criteria: [
                    'Demonstrates good understanding of subject matter',
                    'Participates actively in lessons'
                ]
            });
        }
    });

    // Delegasi Event untuk Hapus Rubrik & Tambah/Hapus Kriteria
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
                alert('Setiap rubrik harus memiliki minimal satu kriteria.');
            }
        }
    });

    function updateRubricNumbers() {
        document.querySelectorAll('.rubric-item').forEach((el, i) => {
            el.querySelector('.rubric-number').textContent = `Rubric ${i + 1}`;
            // Update names for backend indexing
            const catInput = el.querySelector('input[name*="[name]"]');
            const teacherSelect = el.querySelector('select[name*="[teacher_id]"]');
            const criteriaBtn = el.querySelector('.add-criteria');
            const criteriaContainer = el.querySelector('.criteria-container');

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
            const input = item.querySelector('input');
            input.name = `rubrics[${rubricIndex}][criteria][${j}][name]`;
        });
    }
});
</script>
@endsection