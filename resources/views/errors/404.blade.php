@extends('layouts.app')

@section('title', 'Page Not Found')

@section('content')
<div class="text-center">
    <h1 class="display-4">404</h1>
    <p class="lead">Oops! The page you are looking for does not exist.</p>
    <a href="{{ url('/home') }}" class="btn btn-primary">Go to Dashboard</a>
</div>
@endsection