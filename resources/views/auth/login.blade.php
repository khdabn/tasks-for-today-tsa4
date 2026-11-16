@extends('layouts.app')
@section('content')
<div class="form-panel"><h1>Log in</h1><p>Use the manager email and password configured during setup.</p>
<form method="POST" action="{{ route('login') }}">@csrf
<div class="field"><label for="email">Email</label><input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">@error('email')<p class="error">{{ $message }}</p>@enderror</div>
<div class="field"><label for="password">Password</label><input id="password" type="password" name="password" required autocomplete="current-password">@error('password')<p class="error">{{ $message }}</p>@enderror</div>
<div class="field"><label><input type="checkbox" name="remember" value="1"> Remember me</label></div>
<button>Log in</button> <a href="{{ route('password.request') }}">Forgot password?</a>
</form></div>
@endsection
