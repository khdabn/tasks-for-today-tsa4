<?php
namespace Tests\Feature;
use App\Mail\TaskCompleted;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
class TaskWorkflowTest extends TestCase
{
 use RefreshDatabase;
 private function manager(): User {
  return User::create(['name'=>'Test Manager','full_name'=>'Test Manager','username'=>'manager','email'=>'manager@example.com','password'=>Hash::make('TestPassword123!')]);
 }
 private function task(array $data=[]): Task {
  return Task::create(array_merge(['title'=>'Sample task','status'=>'pending','task_date'=>today()->toDateString()],$data));
 }
 public function test_four_pages_are_public_and_today_excludes_other_dates_and_archives(): void {
  $this->manager();
  $this->task(['title'=>'Visible today']);
  $this->task(['title'=>'Yesterday only','task_date'=>today()->subDay()->toDateString()]);
  $archived=$this->task(['title'=>'Hidden archived record 927']);$archived->is_archived=true;$archived->save();
  $this->get('/')->assertOk()->assertSee('Visible today')->assertDontSee('Yesterday only')->assertDontSee('Hidden archived record 927');
  $this->get('/tasks')->assertOk()->assertSee('Yesterday only')->assertDontSee('Archived task');
  $this->get('/profile')->assertOk();$this->get('/about')->assertOk();
 }
 public function test_all_management_endpoints_require_authentication(): void {
  $task=$this->task();
  $this->get('/tasks/create')->assertRedirect('/login');
  $this->get('/tasks/'.$task->id.'/edit')->assertRedirect('/login');
  $this->post('/tasks',['title'=>'Bad','status'=>'pending','task_date'=>today()->toDateString()])->assertRedirect('/login');
  $this->put('/tasks/'.$task->id,['title'=>'Bad','status'=>'done','task_date'=>today()->toDateString()])->assertRedirect('/login');
  $this->delete('/tasks/'.$task->id)->assertRedirect('/login');
  $this->assertDatabaseCount('tasks',1);$this->assertFalse($task->fresh()->is_archived);$this->assertSame('pending',$task->fresh()->status);
 }
 public function test_authenticated_task_creation_and_validation(): void {
  $this->actingAs($this->manager());
  $this->post('/tasks',['title'=>'New task','status'=>'pending','task_date'=>today()->toDateString()])->assertRedirect('/tasks');
  $this->assertDatabaseHas('tasks',['title'=>'New task','is_archived'=>false]);
  $this->from('/tasks/create')->post('/tasks',['title'=>'','status'=>'invalid','task_date'=>'invalid'])
   ->assertRedirect('/tasks/create')->assertSessionHasErrors(['title','status','task_date']);
 }
 public function test_invalid_form_retains_old_input(): void {
  $this->actingAs($this->manager());
  $this->from('/tasks/create')->post('/tasks',['title'=>'Keep my input','status'=>'invalid','task_date'=>today()->toDateString()])
   ->assertSessionHasErrors('status')->assertSessionHasInput('title','Keep my input');
 }
 public function test_done_transition_sends_once_and_already_done_edit_does_not_resend(): void {
  Mail::fake();$this->actingAs($this->manager());$task=$this->task();
  config(['tasks.notification_recipient'=>'notify@example.com']);
  $data=['title'=>'Finished task','status'=>'done','task_date'=>today()->toDateString()];
  $this->put('/tasks/'.$task->id,$data)->assertRedirect('/tasks');
  Mail::assertSent(TaskCompleted::class,fn($mail)=>$mail->hasTo('notify@example.com') && $mail->task->id===$task->id);
  $data['title']='Rename completed task';$this->put('/tasks/'.$task->id,$data)->assertRedirect('/tasks');
  Mail::assertSentCount(1);
 }
 public function test_unchanged_pending_and_creation_as_done_do_not_send_transition_mail(): void {
  Mail::fake();$this->actingAs($this->manager());$task=$this->task();
  $data=['title'=>'Renamed pending','status'=>'pending','task_date'=>today()->toDateString()];
  $this->put('/tasks/'.$task->id,$data)->assertRedirect('/tasks');
  $data['status']='done';$this->post('/tasks',$data)->assertRedirect('/tasks');
  Mail::assertNothingSent();
 }
 public function test_archive_preserves_record_and_blocks_further_editing(): void {
  $this->actingAs($this->manager());$task=$this->task();
  $this->delete('/tasks/'.$task->id)->assertRedirect('/tasks');
  $this->assertDatabaseHas('tasks',['id'=>$task->id,'is_archived'=>true]);
  $this->get('/tasks/'.$task->id.'/edit')->assertNotFound();
  $this->put('/tasks/'.$task->id,['title'=>'Changed','status'=>'done','task_date'=>today()->toDateString()])->assertNotFound();
 }
 public function test_task_list_is_sorted_by_date(): void {
  $this->task(['title'=>'Later task','task_date'=>today()->addDay()->toDateString()]);
  $this->task(['title'=>'Earlier task','task_date'=>today()->subDay()->toDateString()]);
  $this->get('/tasks')->assertOk()->assertSeeInOrder(['Earlier task','Later task']);
 }
 public function test_breeze_login_and_logout(): void {
  $user=$this->manager();
  $this->post('/login',['email'=>$user->email,'password'=>'TestPassword123!'])->assertRedirect();
  $this->assertAuthenticatedAs($user);
  $this->post('/logout')->assertRedirect('/');$this->assertGuest();
 }
 public function test_wrong_password_is_rejected(): void {
  $user=$this->manager();
  $this->post('/login',['email'=>$user->email,'password'=>'WrongPassword'])->assertSessionHasErrors('email');
  $this->assertGuest();
 }
 public function test_mailable_contains_the_completed_task(): void {
  $task=$this->task(['title'=>'Check notification','status'=>'done']);
  (new TaskCompleted($task))->assertSeeInHtml('Check notification')->assertSeeInHtml('Done');
 }
}
