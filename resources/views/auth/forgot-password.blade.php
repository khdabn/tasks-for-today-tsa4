@extends('layouts.app')
@section('content')
<div class="form-panel"><h1>Reset password</h1><p>Enter your account email. In log-mail mode, retrieve the reset link privately from the server log.</p>
<form method="POST" action="{{ route('password.email') }}">@csrf
<div class="field"><label for="email">Email</label><input id="email" type="email" name="email" value="{{ old('email') }}" required>@error('email')<p class="error">{{ $message }}</p>@enderror</div>
<button>Generate password reset link</button></form></div>
@endsection
