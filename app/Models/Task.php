<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
class Task extends Model
{
    protected $fillable=['title','status','task_date'];
    protected function casts(): array { return ['task_date'=>'date','is_archived'=>'boolean']; }
    public function scopeActive(Builder $query): Builder { return $query->where('is_archived',false); }
}
