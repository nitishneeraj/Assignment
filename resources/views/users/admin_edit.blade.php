@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="container py-4">
    <h2>Edit User</h2>

    <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('dashboard.admin') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection