@extends('layouts.app')
@section('content')
<div class="form-panel"><h1>Edit Task</h1>
<form method="POST" action="{{ route('tasks.update',$task) }}">@csrf
@method('PUT')
@include('tasks._form',['buttonText'=>'Edit Task'])
</form></div>
@endsection
