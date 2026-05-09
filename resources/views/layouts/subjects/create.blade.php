@extends('base')

@section('content')
<div class="col-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Subject Form Input</h4>
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
                        <select class="form-select" name="category_subject" required>
                            <option value="">Select Subject</option>
                            @foreach ($subjects as $sub)
                                <option value="{{ $sub->category_subject }}">{{ $sub->category_subject }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="mb-2 fw-bold">Term</label>
                        <select class="form-select" name="term" required>
                            <option value="">Select Term</option>
                            @foreach ($terms as $t)
                                <option value="{{ $t->term }}">{{ $t->term }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label class="mb-2 fw-bold">Year / Class</label>
                    <select name="class_id" class="form-select" required>
                        <option value="Year 1">Year 1</option>
                        <option value="Year 2">Year 2</option>
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
                    <div class="card border mb-3 rubric-item">
                        <div class="card-body bg-white"> <!-- Mengubah bg-light menjadi bg-white -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-secondary rubric-number">Rubric 1</span>
                                <button type="button" class="btn btn-danger btn-sm remove-rubric">Hapus</button>
                            </div>
                            <div class="row">
                                <div class="col-md-8 form-group mb-0">
                                    <label class="small fw-bold mb-2">Name Subject / Item Penilaian</label>
                                    <input type="text" name="rubrics[0][name]" class="form-control" placeholder="Contoh: Shapes and Patterns" required>
                                </div>
                                <div class="col-md-4 form-group mb-0">
                                    <label class="small fw-bold mb-2">Teacher</label>
                                    <select name="rubrics[0][teacher_id]" class="form-select" required>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->teacher_id }}">{{ $teacher->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success me-2">Simpan Semua Data</button>
                    <a href="{{ route('admin.subjects.index') }}" class="btn btn-light border">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('rubrics-container');
    const addButton = document.getElementById('add-rubric');
    let rubricCount = 1;

    addButton.addEventListener('click', function() {
        rubricCount++;
        const newRubric = document.createElement('div');
        newRubric.className = 'card border mb-3 rubric-item';
        // Pastikan template literal JS ini juga menggunakan bg-white dan margin label yang sama
        newRubric.innerHTML = `
            <div class="card-body bg-white">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="badge bg-secondary rubric-number">Rubric ${rubricCount}</span>
                    <button type="button" class="btn btn-danger btn-sm remove-rubric">Hapus</button>
                </div>
                <div class="row">
                    <div class="col-md-8 form-group mb-0">
                        <label class="small fw-bold mb-2">Name Rubric / Item Penilaian</label>
                        <input type="text" name="rubrics[${rubricCount-1}][name]" class="form-control" placeholder="Contoh: Item Baru" required>
                    </div>
                    <div class="col-md-4 form-group mb-0">
                        <label class="small fw-bold mb-2">Teacher</label>
                        <select name="rubrics[${rubricCount-1}][teacher_id]" class="form-select" required>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->teacher_id }}">{{ $teacher->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>`;
        container.appendChild(newRubric);
    });

    container.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-rubric')) {
            const items = document.querySelectorAll('.rubric-item');
            if (items.length > 1) {
                e.target.closest('.rubric-item').remove();
                // Update numbers
                document.querySelectorAll('.rubric-number').forEach((el, i) => {
                    el.textContent = `Subject ${i + 1}`;
                });
            }
        }
    });
});
</script>
@endsection