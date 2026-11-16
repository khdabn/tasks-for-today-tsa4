@if($errors->any())<p class="notice warning" role="alert">Please correct the fields below.</p>@endif
<div class="field"><label for="title">Title</label><input id="title" name="title" maxlength="255" required value="{{ old('title',$task->title ?? '') }}">@error('title')<p class="error">{{ $message }}</p>@enderror</div>
<div class="field"><label for="status">Status</label><select id="status" name="status" required>
 @foreach(['pending'=>'Pending','in_progress'=>'In progress','done'=>'Done'] as $value=>$label)
 <option value="{{ $value }}" @selected(old('status',$task->status ?? 'pending') === $value)>{{ $label }}</option>
 @endforeach
</select>@error('status')<p class="error">{{ $message }}</p>@enderror</div>
<div class="field"><label for="task_date">Task Date</label><input id="task_date" name="task_date" type="date" required value="{{ old('task_date',isset($task) ? $task->task_date->format('Y-m-d') : today()->toDateString()) }}">@error('task_date')<p class="error">{{ $message }}</p>@enderror</div>
<div class="actions"><button type="submit">{{ $buttonText }}</button><a href="{{ route('tasks.index') }}">Cancel</a></div>
