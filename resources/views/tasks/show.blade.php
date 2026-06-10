@extends('layouts.app')
@section('title', 'Task Details')

@section('content')
    <div class="mb-3">
        <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary btn-sm">← Back to List</a>
    </div>

    {{-- 親タスクの名前 --}}
    <div class="card mb-4 bg-light">
        <div class="card-body">
            <h1 class="h3 card-title mb-0">{{ $task->name }}</h1>
        </div>
    </div>

    {{-- 細かいタスク（サブタスク）の管理エリア --}}
    <div class="card">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0"><i class="fas fa-list-ol"></i> 具体的にやることリスト</h5>
        </div>
        <div class="card-body">

            {{-- 1. サブタスクを追加するフォーム --}}
            <form action="{{ route('subtasks.store', $task->id) }}" method="post" class="mb-4">
                @csrf
                <div class="input-group">
                    <input type="text" name="title" class="form-control" placeholder="例：1. 資料を集める / 2. カフェを予約する"
                        required autofocus>
                    <button type="submit" class="btn btn-success">追加</button>
                </div>
            </form>


            {{-- 2. サブタスクの一覧表示（自動で番号を振る） --}}
            @if ($task->subtasks->isEmpty())
                <p class="text-muted small text-center my-4">細かいタスクはまだありません。上のフォームから追加してください！</p>
            @else
                <div class="list-group list-group-numbered">
                    @foreach ($task->subtasks as $subtask)
                        <div class="list-group-item d-flex justify-content-between align-items-center py-3">

                            {{-- クエリパラメータで「?edit_subtask=ID」が指定されている行だけ、入力欄に切り替える --}}
                            @if (request('edit_subtask') == $subtask->id)
                                {{-- 編集用のフォーム --}}
                                <form action="{{ route('subtasks.update', $subtask->id) }}" method="post"
                                    class="w-100 me-3">
                                    @csrf
                                    @method('PATCH')
                                    <div class="input-group input-group-sm">
                                        <input type="text" name="title" class="form-control"
                                            value="{{ $subtask->title }}" required autofocus>
                                        <button type="submit" class="btn btn-warning">保存</button>
                                        <a href="{{ route('tasks.show', $task->id) }}"
                                            class="btn btn-outline-secondary">キャンセル</a>
                                    </div>
                                </form>
                            @else
                                {{-- 通常時の表示（テキスト ＋ 編集・削除ボタン） --}}
                                <div class="ms-2 me-auto">
                                    {{ $subtask->title }}
                                </div>

                                <div class="d-flex gap-3 align-items-center">
                                    {{-- 編集ボタン：クリックするとURLに ?edit_subtask=ID を付与して画面をリロードする --}}
                                    <a href="{{ route('tasks.show', $task->id) }}?edit_subtask={{ $subtask->id }}"
                                        class="btn btn-link text-warning p-0">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    {{-- 削除ボタン（既存のもの） --}}
                                    <form action="{{ route('subtasks.destroy', $subtask->id) }}" method="post"
                                        class="d-inline m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link text-danger p-0"
                                            onclick="return confirm('Delete this step?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            @endif

                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
@endsection
