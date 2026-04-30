@extends('base')
@section('content')
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">List Users</h4>
                <p class="card-description">Add Users:
                    <a href=" {{ route('admin.users.create') }}"> Form input</a>
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
                                <th> Username </th>
                                <th> Email </th>
                                <th> Role </th>
                                <th> Phone Number </th>
                                <th> Action </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    1
                                </td>
                                <td class="py-1">
                                    Reza Rahardian
                                </td>
                                <td> udin88@gmail.com </td>
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
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection()
