<?php
namespace App\Http\Controllers;
use App\Http\Requests\TaskRequest;
use App\Mail\TaskCompleted;
use App\Models\Task;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
class TaskController extends Controller
{
 public function welcome() {
  $tasks=Task::active()->whereDate('task_date',today())->orderBy('id')->get();
  return view('welcome',compact('tasks'));
 }
 public function index() {
  $tasks=Task::active()->orderBy('task_date')->orderBy('id')->paginate(20);
  return view('tasks.index',compact('tasks'));
 }
 public function create() {return view('tasks.create');}
 public function store(TaskRequest $request) {
  Task::create($request->validated());
  return to_route('tasks.index')->with('success','Task created.');
 }
 public function edit(Task $task) {
  abort_if($task->is_archived,404);
  return view('tasks.edit',compact('task'));
 }
 public function update(TaskRequest $request,Task $task) {
  $data=$request->validated();
  // Lock and re-read the row so concurrent updates cannot both see a pending task.
  $completed=DB::transaction(function() use($task,$data){
   $record=Task::whereKey($task->id)->lockForUpdate()->firstOrFail();
   abort_if($record->is_archived,404);
   $notify=$record->status !== 'done' && $data['status'] === 'done';
   $record->update($data);
   return $notify ? $record : null;
  });
  if($completed !== null){
   try {
    Mail::to(config('tasks.notification_recipient'))->send(new TaskCompleted($completed));
   } catch(\Throwable $exception) {
    report($exception);
    return to_route('tasks.index')->with('warning','Task saved, but the completion email failed. Check the server log and mail configuration.');
   }
  }
  return to_route('tasks.index')->with('success',$completed ? 'Task updated. Completion email generated.' : 'Task updated.');
 }
 public function destroy(Task $task) {
  abort_if($task->is_archived,404);
  $task->is_archived=true; $task->save();
  return to_route('tasks.index')->with('success','Task archived. Its database record was preserved.');
 }
}
