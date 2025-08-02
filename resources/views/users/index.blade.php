@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">User Management</h2>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(auth()->user()->hasRole('SuperAdmin') || auth()->user()->hasRole('Admin'))
    <div class="mb-3 text-end">
        <a href="{{ route('users.create') }}" class="btn btn-primary">Create User</a>
    </div>
    @endif

    <table class="table table-bordered table-hover">
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
            @forelse ($users as $index => $user)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ implode(', ', $user->getRoleNames()->toArray()) }}</td>
                <td>
                    <a href="{{ route('users.show', $user->id) }}" class="btn btn-sm btn-info">View</a>

                    @if(auth()->user()->hasRole('SuperAdmin') || auth()->id() === $user->id)
                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    @endif

                    @if(auth()->user()->hasRole('SuperAdmin') && auth()->id() !== $user->id)
                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('Are you sure you want to delete this user?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">No users found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection