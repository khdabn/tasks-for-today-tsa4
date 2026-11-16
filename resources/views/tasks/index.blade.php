@extends('layouts.app')
@section('title','Task List | Tasks for Today')
@section('content')
 <div class="toolbar"><h1>Task List</h1>@auth<a class="button" href="{{ route('tasks.create') }}">Create Task</a>@endauth</div>
 <p>All active tasks, ordered from earliest to latest date. Total: {{ $tasks->total() }}</p>
 @include('tasks._table')
 @if($tasks->hasPages())
 <div class="pagination">
 @if($tasks->onFirstPage())<span>Previous</span>@else<a href="{{ $tasks->previousPageUrl() }}">Previous</a>@endif
 <span>Page {{ $tasks->currentPage() }} of {{ $tasks->lastPage() }}</span>
 @if($tasks->hasMorePages())<a href="{{ $tasks->nextPageUrl() }}">Next</a>@else<span>Next</span>@endif
 </div>
 @endif
@endsection
