@extends('base')
@section('content')
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title">List Users</h4>
                    <a href="{{ route('admin.dashboard.index') }}" class="text-dark text-decoration-none" title="Go to Dashboard">
                        <i class="mdi mdi-arrow-left"></i> Dashboard
                    </a>
                </div>
                <p class="card-description">Add Users:
                    <a href="{{ route('admin.users.create') }}"> Form input</a>
                </p>

                {{-- Form Search --}}
                <form action="{{ route('admin.users.index') }}" class="d-flex col-md-4">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search data user"
                            id="searchInput" value="{{ request('search') }}">
                        <button class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                    </div>
                </form>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                
                <style>
                    .highlight-new {
                        background-color: #e8f5e9 !important;
                        transition: background-color 2s ease;
                    }
                </style>

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
                            @forelse ($users as $user)
                                <tr class="{{ session('new_user_id') == $user->user_id ? 'highlight-new' : '' }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="py-1">{{ $user->username }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <div class="badge badge-opacity-success me-3">
                                            {{ ucfirst($user->role) }}
                                        </div>
                                    </td>
                                    <td>{{ $user->phone_number ?? '-' }}</td>
                                    <td class="text-center align-middle">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('admin.users.edit', $user->user_id) }}" class="btn btn-warning text-white">Edit</a>
                                            <form action="{{ route('admin.users.destroy', $user->user_id) }}" method="POST" onsubmit="return confirm('Delete this user {{$user->username}}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger text-white">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">No users found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{$users->links()}}
                </div>
            </div>
        </div>
    </div>
@endsection
