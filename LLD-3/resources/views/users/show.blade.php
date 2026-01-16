@extends('layouts.app')

@section('content')
<div class="container">
    <h1>User Details</h1>
    
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $user->name }}</h5>
            <p class="card-text"><strong>Email:</strong> {{ $user->email }}</p>
            <p class="card-text"><strong>Created:</strong> {{ $user->created_at->format('M d, Y') }}</p>
            <p class="card-text"><strong>Updated:</strong> {{ $user->updated_at->format('M d, Y') }}</p>
        </div>
    </div>
    
    <div class="mt-3">
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Back to Users</a>
        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning">Edit</a>
        
        <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
        </form>
    </div>
</div>
@endsection
