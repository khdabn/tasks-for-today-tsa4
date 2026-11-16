@extends('layouts.app')
@section('content')
 <h1>Tasks for Today</h1>
 <p class="muted">{{ today()->format('l, F j, Y') }} · Philippine time</p>
 <p>Active tasks scheduled for today. Archived tasks are excluded.</p>
 <div class="summary"><div><strong>{{ $tasks->count() }}</strong>Today's tasks</div><div><strong>{{ $tasks->where('status','done')->count() }}</strong>Done</div></div>
 @auth<a class="button" href="{{ route('tasks.create') }}">Create Task</a>@else<p><a href="{{ route('login') }}">Log in</a> to create, edit, or archive tasks.</p>@endauth
 @include('tasks._table')
@endsection
