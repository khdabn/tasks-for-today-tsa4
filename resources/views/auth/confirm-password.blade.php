@extends('layouts.app')
@section('content')
<div class="form-panel"><h1>Confirm password</h1><form method="POST" action="{{ route('password.confirm') }}">@csrf
<div class="field"><label for="password">Password</label><input id="password" name="password" type="password" required autocomplete="current-password">@error('password')<p class="error">{{ $message }}</p>@enderror</div><button>Confirm</button>
</form></div>
@endsection
