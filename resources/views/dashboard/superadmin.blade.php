@extends('layouts.app')

@section('title', 'SuperAdmin Dashboard')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">SuperAdmin Dashboard</h2>


    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-primary shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-primary">Total Users</h5>
                    <p class="card-text display-6">{{ $totalUsers }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-success shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-success">Admins</h5>
                    <p class="card-text display-6">{{ $totalAdmins }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-warning shadow-sm">
                <div class="card-body">
                    <h5 class="card-title text-warning">Regular Users</h5>
                    <p class="card-text display-6">{{ $totalRegularUsers }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="text-end mb-3">
        <a href="{{ route('users.create') }}" class="btn btn-primary">➕ Create New User</a>
    </div>

    <!-- User List Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            User Overview
        </div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role(s)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $index => $user)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ implode(', ', $user->getRoleNames()->toArray()) }}</td>
                        <td>
                            <!-- <a href="{{ route('users.show', $user->id) }}" class="btn btn-sm btn-info">View</a> -->
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            @if(auth()->id() !== $user->id)
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection