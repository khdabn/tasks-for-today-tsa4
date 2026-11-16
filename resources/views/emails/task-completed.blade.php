<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><title>Task completed</title></head><body>
<h1>Task completed</h1><p>The following task has changed to Done.</p>
<dl><dt>Task ID</dt><dd>{{ $task->id }}</dd><dt>Title</dt><dd>{{ $task->title }}</dd><dt>Scheduled Date</dt><dd>{{ $task->task_date->format('F j, Y') }}</dd><dt>Status</dt><dd>Done</dd></dl>
<p>Tasks for Today Management System</p></body></html>
