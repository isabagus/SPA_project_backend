@extends('base')
@section('content')
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
<<<<<<< HEAD
                <h4 class="card-title">List Subjects</h4>
                <p class="card-description">Add Subject:
                    <a href=" {{ route('admin.subjects.create') }}"> Form input</a>
                </p>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="table-responsive">
                    <table class="table table-striped text-center">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th> Name </th>
                                <th> Term </th>
                                <th> Action </th>
=======
                <h4 class="card-title">List Students</h4>
                <p class="card-description">Add Subject : <a href="{{ route('admin.students.create') }}">Form Input</a>
                </p>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th> Term</th>
                                <th> Name Subject </th>
                                <th> Class </th>
                                <th> Year Academy </th>
                                {{-- <th> Gender </th>
                                <th> Address </th>
                                <th> Class </th>
                                <th> Mentor </th> --}}
                                <th> Aksi </th>
>>>>>>> 54c2d1b8e3839cac0a07e8feb8d33836ea605ce2
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
<<<<<<< HEAD
                                <td>
                                    1
                                </td>
                                <td class="py-1">
                                    Tutus Praningki
                                </td>
                                <td> Praningki86@gmail.com </td>
                                <td>
                                    <div class="badge badge-opacity-success me-3">Teacher</div>
                                </td>
                                <td> 08123456789 </td>
                                <td class="text-center align-middle">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button type="button" class="btn btn-primary text-white">Edit</button>
                                        <button type="button" class="btn btn-danger text-white">Delete</button>
                                    </div>
                                </td>
=======
                                <td>1</td>
                                {{-- <td class="py-1">
                                    <img src="../../assets/images/faces/face1.jpg" alt="image" />
                                </td> --}}
                                <td> Term 2 </td>
                                <td>
                                    {{-- <div class="progress">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 25%"
                                        aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div> --}}
                                    Mathematics
                                </td>
                                <td> Years 1 </td>
                                <td> 2026 </td>
                                {{-- <td> Years 1 </td>
                                <td> 2026/2027 </td> --}}
                                {{-- <td> Mr.Budi </td> --}}
                                <td>
                                    <a href="{{ route('admin.subjects.index') }}" class="btn btn-success text-white">Detail</a>
                                    <button type="button" class="btn btn-primary text-white">Edit</button>
                                    <button type="button" class="btn btn-danger text-white">Delete</button>
                                </td>
                            </tr>
>>>>>>> 54c2d1b8e3839cac0a07e8feb8d33836ea605ce2
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<<<<<<< HEAD
@endsection()
=======
@endsection
>>>>>>> 54c2d1b8e3839cac0a07e8feb8d33836ea605ce2
