<div class="table-wrap"><table>
 <thead><tr><th scope="col">ID</th><th scope="col">Title</th><th scope="col">Status</th><th scope="col">Task Date</th>@auth<th scope="col">Actions</th>@endauth</tr></thead>
 <tbody>
 @forelse($tasks as $task)
  <tr><td>{{ $task->id }}</td><td>{{ $task->title }}</td><td><span class="status {{ $task->status }}">{{ str_replace('_',' ',ucfirst($task->status)) }}</span></td><td>{{ $task->task_date->format('M j, Y') }}</td>
  @auth<td><div class="actions"><a href="{{ route('tasks.edit',$task) }}">Edit</a>
   <form method="POST" action="{{ route('tasks.destroy',$task) }}" onsubmit="return confirm('Archive this task? It will disappear from the public lists.');">@csrf @method('DELETE')<button class="danger" type="submit">Archive</button></form>
  </div></td>@endauth</tr>
 @empty<tr><td class="empty" colspan="{{ auth()->check() ? 5 : 4 }}">No tasks to display.</td></tr>@endforelse
 </tbody>
</table></div>
