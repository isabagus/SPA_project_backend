@extends('base')
@section('content')
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Subject Form Input</h4>
                <p class="card-description"> Add Multiple Subjects under one Category </p>
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form class="forms-sample" method="POST" action="{{ route('admin.subjects.store') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Subject Category (Header)</label>
                            <select class="form-select" name="category_subject" required>
                                <option value="">Select Subject</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->category_subject }}">{{ $cat->category_subject }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Term</label>
                            <select class="form-select" name="term" required>
                                <option value="">Select Term</option>
                                @foreach ($terms as $t)
                                    <option value="{{ $t->term }}">{{ $t->term }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <hr>
                    <h5 class="mb-3">Assessment Items (Sub-Subjects)</h5>

                    <div id="subject-items-container">
                        <!-- Single Subject Block -->
                        <div class="subject-block border p-3 mb-3 position-relative rounded">
                            <h6 class="mb-3 item-title">Subject 1</h6>
                            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 remove-item-btn" style="display: none;"><i class="fa fa-times"></i> Hapus</button>
                            
                            <div class="form-group mb-0">
                                <label>Name Subject / Item Penilaian</label>
                                <input type="text" class="form-control" name="name_subject[]" placeholder="Contoh: Observing / Physical Education" required>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <button type="button" class="btn btn-success" id="add-subject-btn">
                            <i class="fa fa-plus"></i> Add Subject Form
                        </button>
                    </div>

                    <div class="mt-4 border-top pt-3">
                        <button type="submit" class="btn btn-primary me-2">Submit All Subjects</button>
                        <a href="{{ route('admin.subjects.index') }}" class="btn btn-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('subject-items-container');
            const addBtn = document.getElementById('add-subject-btn');
            
            function updateTitlesAndButtons() {
                const blocks = container.querySelectorAll('.subject-block');
                blocks.forEach((block, index) => {
                    block.querySelector('.item-title').textContent = 'Subject ' + (index + 1);
                    
                    const removeBtn = block.querySelector('.remove-item-btn');
                    if (blocks.length > 1) {
                        removeBtn.style.display = 'inline-block';
                    } else {
                        removeBtn.style.display = 'none';
                    }
                });
            }

            addBtn.addEventListener('click', function() {
                // Clone the first block as template
                const firstBlock = container.querySelector('.subject-block');
                const newBlock = firstBlock.cloneNode(true);
                
                // Clear input value
                newBlock.querySelector('input').value = '';
                
                // Add event listener to new remove button
                newBlock.querySelector('.remove-item-btn').addEventListener('click', function() {
                    newBlock.remove();
                    updateTitlesAndButtons();
                });
                
                container.appendChild(newBlock);
                updateTitlesAndButtons();
            });

            // Add event listener to the initial remove button
            container.querySelector('.remove-item-btn').addEventListener('click', function(e) {
                const block = e.target.closest('.subject-block');
                if (container.querySelectorAll('.subject-block').length > 1) {
                    block.remove();
                    updateTitlesAndButtons();
                }
            });
        });
    </script>
@endsection
