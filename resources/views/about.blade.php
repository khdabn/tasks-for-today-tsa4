@extends('layouts.app')
@section('content')
<h1>About This Application</h1>
<p>Tasks for Today Management System was developed by <strong>ANGELO P. BUEN</strong> for IT0049 Technical Summative Assessment 4.</p>
<p>The application uses Laravel, Eloquent, MySQL, Blade, and Laravel Breeze authentication. Everyone can view the four public pages. Logged-in users can create, edit, and archive tasks.</p>
<p>Archiving preserves a task in the database through the is_archived flag. Changing a task to Done generates a completion email; editing a task that is already Done does not generate another email.</p>
@endsection
