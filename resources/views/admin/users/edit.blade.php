@extends('layouts.template')

@section('title', 'Edit User')

@section('main')
    <h1>Edit user: {{ $user->name }}</h1>
    <form action="/admin/users/{{ $user->id }}" method="post">
        @method('put')
        @csrf
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name"
                   class="form-control @error('name') is-invalid @enderror"
                   placeholder="Name"
                   minlength="2"
                   required
                   value="{{ old('name', $user->name ) }}"
            >
            @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email"
                   class="form-control @error('email') is-invalid @enderror"
                   placeholder="Your email"
                   value="{{ old('email', $user->email) }}"
                   required>
            @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="active" name="active" value="true"
                       @if (old('active', $user->active) == 1)
                       checked
                    @endif
                >
                <label class="form-check-label" for="active">Active</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="admin" name="admin" value="true"
                       @if (old('admin', $user->admin) == 1)
                       checked
                    @endif
                >
                <label class="form-check-label" for="admin">Admin</label>
            </div>
        </div>

        <button type="submit" class="btn btn-success">Save user</button>
    </form>
@endsection
