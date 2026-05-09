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
                        <option value="Year 1" {{ $subject->level_class == 'Year 1' ? 'selected' : '' }}>Year 1</option>
                        <option value="Year 2" {{ $subject->level_class == 'Year 2' ? 'selected' : '' }}>Year 2</option>
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
                        <div class="card border mb-3 rubric-item shadow-sm">
                            <div class="card-body bg-white">
                                {{-- Input hidden untuk rubric_id agar sistem tahu ini data lama --}}
                                <input type="hidden" name="rubrics[{{ $index }}][rubric_id]" value="{{ $rubric->rubric_id }}">
                                
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge bg-secondary rubric-number">Rubric {{ $index + 1 }}</span>
                                    <button type="button" class="btn btn-danger btn-sm remove-rubric">Hapus</button>
                                </div>
                                <div class="row">
                                    <div class="col-md-8 form-group mb-0">
                                        <label class="small fw-bold mb-2">Rubric Name / Item Penilaian</label>
                                        <input type="text" name="rubrics[{{ $index }}][name]" value="{{ $rubric->rubric_name }}" class="form-control" required>
                                    </div>
                                    <div class="col-md-4 form-group mb-0">
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
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 pt-3 border-top">
                    <button type="submit" class="btn btn-success me-2 px-4">Update Semua Data</button>
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
    // Start index dari jumlah rubrik yang sudah ada
    let rubricCount = document.querySelectorAll('.rubric-item').length;

    addButton.addEventListener('click', function() {
        rubricCount++;
        const newRubric = document.createElement('div');
        newRubric.className = 'card border mb-3 rubric-item shadow-sm';
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
                // Update label nomor urut
                document.querySelectorAll('.rubric-number').forEach((el, i) => {
                    el.textContent = `Subject ${i + 1}`;
                });
            } else {
                alert('Minimal harus ada satu item penilaian.');
            }
        }
    });
});
</script>
@endsection
