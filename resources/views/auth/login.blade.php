@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Login</h2>


    <div class="alert alert-info">
        <strong>Demo Login:</strong> <br>
        <strong>Email:</strong> super@gmail.com <br>
        <strong>Password:</strong>
        <span id="demo-password">********</span>
        <button type="button" class="btn btn-sm btn-outline-secondary ms-2" onclick="togglePassword()">Show</button>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger">
        {{ $errors->first() }}
    </div>
    @endif


    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required autofocus>
        </div>

        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button class="btn btn-primary">Login</button>
    </form>
</div>
@endsection

<script>
    function togglePassword() {
        const passwordSpan = document.getElementById('demo-password');
        const button = event.target;

        if (passwordSpan.innerText === '********') {
            passwordSpan.innerText = 'password';
            button.innerText = 'Hide';
        } else {
            passwordSpan.innerText = '********';
            button.innerText = 'Show';
        }
    }
</script>