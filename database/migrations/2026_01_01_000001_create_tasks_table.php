<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {Schema::create('tasks',function(Blueprint $table){
  $table->id();$table->string('title',255);$table->string('status',20)->default('pending');
  $table->date('task_date')->index();$table->boolean('is_archived')->default(false)->index();$table->timestamps();
 });}
 public function down(): void {Schema::dropIfExists('tasks');}
};
