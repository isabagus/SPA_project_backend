@extends('base')
@section('content')
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
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
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
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
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
