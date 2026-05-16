@extends('base')
@section('content')
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title">List Academic Reports</h4>
                    <a href="{{ route('admin.dashboard.index') }}" class="text-dark text-decoration-none" title="Go to Dashboard">
                        <i class="mdi mdi-arrow-left"></i> Dashboard
                    </a>
                </div>
                <p class="card-description">Manage student performance records</p>
                
                {{-- Form Search --}}
                <form action="{{ route('admin.reports.index') }}" class="d-flex col-md-4 mb-3">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search report data..."
                            id="searchInput" value="{{ request('search') }}">
                        <button class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th> Academic Year </th>
                                <th> Level Class </th>
                                <th> Actions </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($classes as $cls)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td> {{ $cls->academic_year }} </td>
                                    <td> {{ $cls->level_class }} </td>
                                    <td>
                                        <a href="{{ route('admin.reports.students', [$cls->class_id, $cls->academic_year]) }}"
                                            class="btn btn-primary text-white"> 
                                            <i class="mdi mdi-account-group"></i> View Students
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No report groups available</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{-- Pagination removed for simple list, or kept if needed --}}
                </div>
            </div>
        </div>
    </div>
@endsection
