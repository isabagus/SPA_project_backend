@extends('base')
@section('content')
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Edit Mentor Profile</h4>
                <p class="card-description"> Update Mentor Data </p>
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form class="forms-sample" method="POST" action="{{ route('admin.mentors.update', $mentor->mentor_id) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label for="user_id">Select User Account</label>
                        <select name="user_id" class="form-control" id="user_id" required>
                            <option value="">-- Select User --</option>
                            @foreach ($usersMentor as $user)
                                <option value="{{ $user->user_id }}" {{ (old('user_id') ?? $mentor->user_id) == $user->user_id ? 'selected' : '' }}>
                                    {{ $user->username }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="name_mentor">Name Mentor</label>
                        <input type="text" name="name_mentor" class="form-control" id="name_mentor" placeholder="Mentor Name" value="{{ old('name_mentor') ?? $mentor->name_mentor }}" required>
                    </div>

                    <div class="form-group">
                        <label for="nip">NIP</label>
                        <input type="text" name="nip" class="form-control" id="nip" placeholder="NIP" value="{{ old('nip') ?? $mentor->nip }}">
                    </div>

                    <div class="form-group">
                        <label for="phone_number">Phone Number</label>
                        <input type="text" name="phone_number" class="form-control" id="phone_number" placeholder="Phone Number" value="{{ old('phone_number') ?? $mentor->phone_number }}">
                    </div>

                    <button type="submit" class="btn btn-primary me-2">Update Mentor</button>
                    <a href="{{ route('admin.mentors.index') }}" class="btn btn-light">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection
