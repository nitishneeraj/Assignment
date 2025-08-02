@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Admin Dashboard</h2>

    <p>Welcome, Admin! Here you can manage users and their permissions.</p>

    {{-- Success Message --}}
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <p><strong>Total Regular Users:</strong> {{ $totalRegularUsers }}</p>
            <p><strong>Total Admins:</strong> {{ $totalAdmins }}</p>
            <p><strong>Total Users (Visible to You):</strong> {{ $totalUsers }}</p>
        </div>

        <!-- <div>
            <a href="{{ route('users.create') }}" class="btn btn-primary">Create User</a>
        </div> -->
    </div>

    {{-- Users Table --}}
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Roles</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $index => $user)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @foreach($user->roles as $role)
                    <span class="badge bg-primary">{{ $role->name }}</span>
                    @endforeach
                </td>
                <td>
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-warning">Edit</a>

                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('Are you sure you want to delete this user?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Bulk User Creation Form --}}
    <hr>
    <h4 class="mt-5 mb-3">Bulk User Creation</h4>
    <form action="{{ route('admin.users.bulkStore') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="csv_file" class="form-label">Upload CSV File</label>
            <input type="file" name="csv_file" class="form-control" accept=".csv" required>
            <small class="text-muted">CSV Format: name,email,password</small>
        </div>
        <button type="submit" class="btn btn-success">Upload and Queue</button>
    </form>
</div>
@endsection