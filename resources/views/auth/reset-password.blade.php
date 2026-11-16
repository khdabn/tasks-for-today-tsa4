@extends('layouts.app')
@section('content')
<div class="form-panel"><h1>Choose a new password</h1>
<form method="POST" action="{{ route('password.store') }}">@csrf
<input type="hidden" name="token" value="{{ $request->route('token') }}">
<div class="field"><label for="email">Email</label><input id="email" type="email" name="email" required value="{{ old('email',$request->email) }}">@error('email')<p class="error">{{ $message }}</p>@enderror</div>
<div class="field"><label for="password">New password</label><input id="password" type="password" name="password" required autocomplete="new-password">@error('password')<p class="error">{{ $message }}</p>@enderror</div>
<div class="field"><label for="password_confirmation">Confirm password</label><input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"></div>
<button>Reset password</button></form></div>
@endsection
