@extends('layouts.app')
@section('title', 'Home')

@section('content')
<div class="container py-4" style="max-width: 600px;">
    <h2 class="mb-4 fw-bold">My Tasks</h2>

    {{-- 入力フォーム --}}
    <form action="{{ route('tasks.store') }}" method="post" class="shadow-sm mb-5">
        @csrf
        <div class="input-group">
            <input type="text" name="name" class="form-control form-control-lg border-0" placeholder="新しいタスクを追加..." autofocus>
            <button type="submit" class="btn btn-success px-4">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </form>

    {{-- タスクリスト --}}
    @if ($all_tasks->isEmpty())
        <p class="text-center text-muted py-4">タスクはまだありません。</p>
    @else
        <div class="task-list">
    <h5 class="mb-3 fw-bold text-dark">Tasks List</h5>
    
    @foreach ($all_tasks as $task)
        <!-- ここを list-group-item から task-card に変更 -->
        <div class="task-card">
            <div class="task-info">
                <div class="fw-bold text-dark">{{ $task->name }}</div>
                @if ($task->description)
                    <p class="text-muted small mt-1">{!! nl2br(e($task->description)) !!}</p>
                @endif
            </div>

            <div class="d-flex gap-2 task-actions">
                <a href="{{ route('tasks.show', $task->id) }}" class="btn-icon btn-view"><i class="fas fa-eye"></i></a>
                <a href="{{ route('tasks.edit', $task->id) }}" class="btn-icon btn-edit"><i class="fas fa-edit"></i></a>
                <form action="{{ route('tasks.destroy', $task->id) }}" method="post" class="d-inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-icon btn-delete" onclick="return confirm('削除しますか？')">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </form>
            </div>
        </div>
    @endforeach
</div>
    @endif
</div>
@endsection