<?php
namespace Database\Seeders;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
class DatabaseSeeder extends Seeder
{
 public function run(): void {
  $email=env('ADMIN_EMAIL','buen@example.com');
  if (!User::where('email',$email)->exists()) {
   $password=env('ADMIN_PASSWORD');
   if (!$password || strlen($password)<12) {throw new \RuntimeException('Set ADMIN_PASSWORD in .env to a unique password of at least 12 characters before seeding.');}
   $name=env('ADMIN_NAME','ANGELO P. BUEN');
   User::create(['name'=>$name,'full_name'=>$name,'username'=>env('ADMIN_USERNAME','buen'),'email'=>$email,'password'=>Hash::make($password)]);
  }
  $rows=[[-1,'Review Laravel migrations','done'],[-1,'Design the tasks table','done'],[0,'Build the Welcome page','pending'],
   [0,'Validate the task forms','pending'],[0,'Test completion notifications','pending'],[0,'Review authentication','pending'],
   [1,'Update the project README','pending'],[1,'Deploy the application','pending']];
  foreach($rows as [$offset,$title,$status]){
   Task::firstOrCreate(['title'=>$title],['status'=>$status,'task_date'=>today()->addDays($offset)->toDateString()]);
  }
 }
}
