@extends('layouts.app')
@section('content')
<div class="form-panel"><h1>Create Task</h1>
<form method="POST" action="{{ route('tasks.store') }}">@csrf

@include('tasks._form',['buttonText'=>'Create Task'])
</form></div>
@endsection
