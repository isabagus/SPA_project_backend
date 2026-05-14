@extends('base')
@section('content')
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Student Form Input</h4>
                    <a href="{{ route('admin.students.index') }}" class="text-dark text-decoration-none" title="Back to List">
                        <i class="mdi mdi-arrow-left"></i> Students
                    </a>
                </div>
                <p class="card-description"> Add Multiple Students </p>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form class="forms-sample" id="bulkStudentForm" method="POST" action="{{ route('admin.students.store') }}">
                    @csrf
                    
                    <div id="student-forms-container">
                        <!-- Single Student Block -->
                        <div class="student-block border p-3 mb-3 position-relative rounded">
                            <h5 class="mb-3 student-title">Data Siswa 1</h5>
                            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 remove-student-btn" style="display: none;"><i class="fa fa-times"></i> Hapus</button>
                            
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Academic Year</label>
                                    <select class="form-select" name="academic_year[]" required>
                                        <option value="">Select Academic Year</option>
                                        @foreach ($academic_years as $year)
                                            <option value="{{ $year->academic_year }}">{{ $year->academic_year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Level Class</label>
                                    <select class="form-select" name="level_class[]" required>
                                        <option value="">Select Level Class</option>
                                        @foreach ($level_classes as $class)
                                            <option value="{{ $class->level_class }}">{{ $class->level_class }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-3 form-group">
                                    <label>NIS</label>
                                    <input type="text" class="form-control" name="nis[]" placeholder="NIS" required>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Name Student</label>
                                    <input type="text" class="form-control" name="name_student[]" placeholder="Name Student" required>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Gender</label>
                                    <select class="form-select" name="gender[]" required>
                                        <option value="">Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label>Religion</label>
                                    <select class="form-select" name="religion_name[]" required>
                                        <option value="">Select Religion</option>
                                        @foreach ($religions as $religion)
                                            <option value="{{ $religion->religion_name }}">{{ $religion->religion_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Address</label>
                                <textarea class="form-control" name="address[]" placeholder="Address" rows="2" required></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="text" class="form-control" name="phone_number[]" placeholder="Phone Number" required>
                            </div>
                        </div>
                        <!-- End Single Student Block -->
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <button type="button" class="btn btn-success" id="add-student-btn">
                            <i class="fa fa-plus"></i> Tambah Form Siswa
                        </button>
                    </div>

                    <hr>

                    <button type="submit" class="btn btn-primary me-2">Submit All Students</button>
                    <a href="{{ route('admin.students.index') }}" class="btn btn-light">Cancel</a>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript for Dynamic Forms -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('student-forms-container');
            const addBtn = document.getElementById('add-student-btn');
            
            // Template untuk form input dinamis 
            const templateBlock = container.querySelector('.student-block').cloneNode(true);
            
            // Mereset value input template form
            templateBlock.querySelectorAll('input').forEach(input => input.value = '');
            templateBlock.querySelectorAll('select').forEach(select => select.value = '');
            templateBlock.querySelectorAll('textarea').forEach(textarea => textarea.value = '');
            templateBlock.querySelector('.remove-student-btn').style.display = 'inline-block';
            
            function updateTitlesAndButtons() {
                const blocks = container.querySelectorAll('.student-block');
                blocks.forEach((block, index) => {
                    block.querySelector('.student-title').textContent = 'Data Siswa ' + (index + 1);
                    
                    // logic show button delete if more than 1 form
                    const removeBtn = block.querySelector('.remove-student-btn');
                    if (blocks.length > 1) {
                        removeBtn.style.display = 'inline-block';
                    } else {
                        removeBtn.style.display = 'none';
                    }
                });
            }

            addBtn.addEventListener('click', function() {
                const newBlock = templateBlock.cloneNode(true);
                
                // Add event listener to new remove button
                newBlock.querySelector('.remove-student-btn').addEventListener('click', function() {
                    newBlock.remove();
                    updateTitlesAndButtons();
                });
                
                container.appendChild(newBlock);
                updateTitlesAndButtons();
            });

            // Add event listener to the initial remove button
            container.querySelector('.remove-student-btn').addEventListener('click', function(e) {
                const block = e.target.closest('.student-block');
                if (container.querySelectorAll('.student-block').length > 1) {
                    block.remove();
                    updateTitlesAndButtons();
                }
            });
        });
    </script>
@endsection
