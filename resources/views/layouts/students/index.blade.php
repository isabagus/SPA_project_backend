@extends('base')
@section('content')
<<<<<<< HEAD
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">List Students</h4>
            <p class="card-description"><a href="#" class="btn btn-success text-white btn-sm"><i class="mdi mdi-plus"></i> Add Student</a> 
            </p> 
          {{-- Form Search --}}
          <form action="{{ route('admin.students.index')}}" class="d-flex col-md-4" >
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search data student" id="searchInput">
                <button class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
            </div>
          </form>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th> Picture </th>
                            <th> Name </th>
                            <th> NIS </th>
                            <th> Gender </th>
                            <th> Address </th>
                            <th> Class </th>
                            <th> Year Academy </th>
                            <th> Mentor </th>
                            <th> Actions </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td class="py-1">
                                <img src="../../assets/images/faces/face1.jpg" alt="image" />
                            </td>
                            <td> Herman Beck </td>
                            <td>
                                {{-- <div class="progress">
=======
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">List Students</h4>
                <p class="card-description">Add Student<a href="{{ route('admin.students.create') }}">Form Input</a>
                </p>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th> Picture </th>
                                <th> Name </th>
                                <th> NIS </th>
                                <th> Gender </th>
                                <th> Address </th>
                                <th> Class </th>
                                <th> Year Academy </th>
                                <th> Mentor </th>
                                <th> Aksi </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td class="py-1">
                                    <img src="../../assets/images/faces/face1.jpg" alt="image" />
                                </td>
                                <td> Herman Beck </td>
                                <td>
                                    {{-- <div class="progress">
>>>>>>> 54c2d1b8e3839cac0a07e8feb8d33836ea605ce2
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 25%"
                                        aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div> --}}
                                    26303019
                                </td>
                                <td> Male</td>
                                <td> Street 1 </td>
                                <td> Years 1 </td>
                                <td> 2026/2027 </td>
                                <td> Mr.Budi </td>
                                <td>

                                    <button type="button" class="btn btn-primary text-white">Edit</button>
                                    <button type="button" class="btn btn-danger text-white">Delete</button>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td class="py-1">
                                    <img src="../../assets/images/faces/face2.jpg" alt="image" />
                                </td>
                                <td> Messsy Adam </td>
                                <td>
                                    {{-- <div class="progress">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 25%"
                                        aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div> --}}
                                    26303019
                                </td>
                                <td> Female</td>
                                <td> Street 1 </td>
                                <td> Years 1 </td>
                                <td> 2026/2027 </td>
                                <td> Mr.Budi </td>
                                <td>

                                    <button type="button" class="btn btn-primary text-white">Edit</button>
                                    <button type="button" class="btn btn-danger text-white">Delete</button>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td class="py-1">
                                    <img src="../../assets/images/faces/face3.jpg" alt="image" />
                                </td>
                                <td> John Richards </td>
                                <td>
                                    {{-- <div class="progress">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 25%"
                                        aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div> --}}
                                    26303019
                                </td>
                                <td> Male</td>
                                <td> Street 1 </td>
                                <td> Years 1 </td>
                                <td> 2026/2027 </td>
                                <td> Mr.Budi </td>
                                <td>

                                    <button type="button" class="btn btn-primary text-white">Edit</button>
                                    <button type="button" class="btn btn-danger text-white">Delete</button>
                                </td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td class="py-1">
                                    <img src="../../assets/images/faces/face5.jpg" alt="image" />
                                </td>
                                <td> Edward </td>
                                <td>
                                    {{-- <div class="progress">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 25%"
                                        aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div> --}}
                                    26303019
                                </td>
                                <td> Male</td>
                                <td> Street 1 </td>
                                <td> Years 1 </td>
                                <td> 2026/2027 </td>
                                <td> Mr.Budi </td>
                                <td>

                                    <button type="button" class="btn btn-primary text-white">Edit</button>
                                    <button type="button" class="btn btn-danger text-white">Delete</button>
                                </td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td class="py-1">
                                    <img src="../../assets/images/faces/face6.jpg" alt="image" />
                                </td>
                                <td> John Doe </td>
                                <td>
                                    {{-- <div class="progress">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 25%"
                                        aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div> --}}
                                    26303019
                                </td>
                                <td> Female</td>
                                <td> Street 1 </td>
                                <td> Years 1 </td>
                                <td> 2026/2027 </td>
                                <td> Mr.Budi </td>
                                <td>

                                    <button type="button" class="btn btn-primary text-white">Edit</button>
                                    <button type="button" class="btn btn-danger text-white">Delete</button>
                                </td>
                            </tr>
                            <tr>
                                <td>6</td>
                                <td class="py-1">
                                    <img src="../../assets/images/faces/face7.jpg" alt="image" />
                                </td>
                                <td> Henry Tom </td>
                                <td>
                                    {{-- <div class="progress">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 25%"
                                        aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div> --}}
                                    26303019
                                </td>
                                <td> Male</td>
                                <td> Street 1 </td>
                                <td> Years 1 </td>
                                <td> 2026/2027 </td>
                                <td> Mr.Budi </td>
                                <td>

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
