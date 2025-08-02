@extends('layouts.app')

@section('title', 'User Dashboard')

@section('content')
<div class="container py-4">
    <h2>Welcome, {{ $user->name }}</h2>
    <p>You are logged in as a regular user.</p>

    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Your Profile</span>
            <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-outline-primary">Edit Profile</a>
        </div>
        <div class="card-body">
            <p><strong>Name:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Role:</strong>
                @foreach($user->roles as $role)
                <span class="badge bg-primary">{{ $role->name }}</span>
                @endforeach
            </p>
        </div>
    </div>
</div>
@endsection