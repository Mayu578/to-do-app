@extends('layouts.app')
@section('title', 'Edit Task')

@section('content')
<h1 class="h3">Edit Task</h1>

{{-- 送信先を tasks.update にし、タスクのIDを渡す --}}
<form action="{{ route('tasks.update', $task->id) }}" method="post">
    @csrf
    @method('PATCH') {{-- Laravelにこれが更新（PATCH）通信だと教える魔法のタグ --}}

    <div class="mb-3">
        <label for="name" class="form-label">Task Name</label>
        {{-- value属性に、元々DBに保存されていたタスクの名前を初期値として表示させる --}}
        <input type="text" name="name" id="name" class="form-control" value="{{ $task->name }}" autofocus>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-warning">Update Task</button>
        <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>
@endsection