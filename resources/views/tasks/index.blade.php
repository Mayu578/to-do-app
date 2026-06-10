@extends('layouts.app')
@section('title', 'Home')

@section('content')
    <h1 class="h3">{{ config('app.name') }}</h1>

    <form action="{{ route('tasks.store') }}" method="post">
        @csrf
        {{-- cross-site request forgeries --}}
        {{-- validate request / security / for CSRF protection --}}

        <div class="row gx-2 mb-3">
            <div class="col-10">
                <input type="text" name="name" id="name" placeholder="Create a task" class="form-control" autofocus>
            </div>
            <div class="col-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-plus"></i>Add
                </button>
            </div>
            {{-- Error --}}
        </div>
    </form>

    {{-- === ここから追加：タスク一覧の表示エリア === --}}
    <hr class="my-4">

    <div class="task-list">
        <h5 class="mb-3">Tasks List</h5>

        {{-- もしタスクが1つもない場合の表示 --}}
        @if ($all_tasks->isEmpty())
            <p class="text-muted small">No tasks yet! Add your first task above.</p>
        @else
            {{-- タスクがある場合は、1つずつ取り出してリスト表示 --}}
            {{-- タスクがある場合は、1つずつ取り出してリスト表示 --}}
            <ul class="list-group">
                @foreach ($all_tasks as $task)
                    <li class="list-group-item d-flex justify-content-between align-items-center">

                        {{-- 左側：タスク名と説明文（description） --}}
                        <div>
                            <div class="fw-bold">{{ $task->name }}</div>
                            @if ($task->description)
                                {{-- 1行ずつ追加した細かいタスクがある場合はここに表示されます --}}
                                <small class="text-muted d-block mt-1">{!! nl2br(e($task->description)) !!}</small>
                            @endif
                        </div>

                        {{-- 右側：各操作ボタン（Show, Edit, Delete） --}}
                        <div class="d-flex gap-2">
                            {{-- 詳細画面へのリンク --}}
                            <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-outline-info btn-sm">
                                <i class="fas fa-eye"></i> Show
                            </a>

                            {{-- 編集画面へのリンク --}}
                            <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-outline-warning btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>

                            {{-- 削除用のフォーム --}}
                            <form action="{{ route('tasks.destroy', $task->id) }}" method="post" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm"
                                    onclick="return confirm('Are you sure you want to delete this task?')">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </button>
                            </form>
                        </div> {{-- ← 足りていなかった閉じタグを補完 --}}

                    </li>
                @endforeach
            </ul>
        @endif
    </div>
    {{-- === ここまで追加 === --}}

@endsection
