<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class TaskRequest extends FormRequest
{
 public function authorize(): bool {return $this->user() !== null;}
 public function rules(): array {return [
  'title'=>['required','string','max:255'],
  'status'=>['required',Rule::in(['pending','in_progress','done'])],
  'task_date'=>['required','date_format:Y-m-d'],
 ];}
}
