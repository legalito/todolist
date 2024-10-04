<h1>Todo List</h1>

<form action="{{ route('tasks.store') }}" method="POST">
    @csrf
    <input type="text" name="title" placeholder="New Task">
    <button type="submit">Add Task</button>
</form>

<ul>
    @foreach($tasks as $task)
        <li>
            <form action="{{ route('tasks.update', $task->id) }}" method="POST" style="display: inline;">
                @csrf
                @method('PUT')
                <input type="checkbox" name="is_completed" onchange="this.form.submit()" {{ $task->is_completed ? 'checked' : '' }}>
                {{ $task->title }}
            </form>
            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit">Delete</button>
            </form>
        </li>
    @endforeach
</ul>
