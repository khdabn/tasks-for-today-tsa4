@extends('layouts.app')
@section('content')
<h1>Profile</h1><p>Developer and task manager profile.</p>
<dl><dt>Username</dt><dd>{{ $user->username }}</dd><dt>Full Name</dt><dd>{{ $user->full_name }}</dd><dt>Email</dt><dd>{{ $user->email }}</dd></dl>
@endsection
