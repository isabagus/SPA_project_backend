@extends('base')
@section('content')
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">User Form Input</h4>
                <p class="card-description"> Add User </p>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form class="forms-sample" method="POST" action="{{ route('admin.mentors.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="name">Name Mentor</label>
                        <input type="text" name="name" class="form-control" id="name" placeholder="Mentor Name" value="{{ old('username') }}">
                    </div>
                    <div class="form-group">
                        <label for="phone_number">Phone Number</label>
                        <input type="phone_number" name="phone_number" class="form-control" id="phone_number" placeholder="Email" value="{{ old('email') }}">
                    </div>

                    <button type="submit" class="btn btn-primary me-2">Submit</button>
                    <a href="{{ route('admin.mentors.index') }}" class="btn btn-light">Cancel</a>
                </form>

                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
            </div>
        </div>
    </div>
@endsection()
