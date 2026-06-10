<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Taskモデルへのアクセス
use App\Models\Task;

class TaskController extends Controller
{
    // Taskモデルのオブジェクトを保持するプロパティ
    private $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    // 1. タスク一覧ページを開く
    public function index()
    {
        // DBからすべてのタスクを取得して画面に渡す（後で一覧表示するために便利です）
        $all_tasks = $this->task->all();
        return view('tasks.index')->with('all_tasks', $all_tasks);
    }

    // 2. フォームから送信された新しいタスクをDBに保存する（★ここを追加！）
    public function store(Request $request)
    {
        // フォームの入力チェック（空欄での送信を防ぐバリデーション）
        $request->validate([
            'name' => 'required|max:255'
        ]);

        // フォームから届いた「name」を、データベースの「name」カラムに保存
        $this->task->name = $request->name;
        $this->task->save();

        // 保存が完了したら、一覧ページ（tasks.indexルート）に画面を戻す
        return redirect()->route('tasks.index');
    }

    // タスクを削除する（★ここを追加！）
    public function destroy($id)
    {
        // URLから届いた $id を使って、消したいタスクをDBから探す
        $task = $this->task->findOrFail($id);

        // 見つかったタスクを削除
        $task->delete();

        // 削除が終わったら、一覧ページに画面を戻す
        return redirect()->route('tasks.index');
    }

    // 1. 編集画面を開く（★ここを追加！）
    public function edit($id)
    {
        // 編集したいタスクをDBから1件取得
        $task = $this->task->findOrFail($id);

        // 編集用の画面（tasks.edit）にタスクのデータを渡して開く
        return view('tasks.edit')->with('task', $task);
    }

    // 2. タスクを更新する（★ここを追加！）
    public function update(Request $request, $id)
    {
        // 入力チェック
        $request->validate([
            'name' => 'required|max:255'
        ]);

        // 更新したいタスクをDBから取得して上書き保存
        $task = $this->task->findOrFail($id);
        $task->name = $request->name;
        $task->save();

        // 更新が終わったら、トップページ（一覧）に戻る
        return redirect()->route('tasks.index');
    }

    // 1. 詳細画面を表示する
    public function show($id)
    {
        // with('subtasks') をつけることで、紐づく子タスクも一緒に一瞬で取得できます
        $task = $this->task->with('subtasks')->findOrFail($id);
        return view('tasks.show')->with('task', $task);
    }

    // 2. 詳細メモを保存・更新する
    public function updateDescription(Request $request, $id)
    {
        $task = $this->task->findOrFail($id);

        // descriptionを上書き保存
        $task->description = $request->description;
        $task->save();

        // 再度詳細画面にリダイレクトして戻る
        return redirect()->route('tasks.index', $id);
    }

    // サブタスクを保存する
    public function storeSubtask(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|max:255'
        ]);

        // Subtaskモデルを使って新規登録
        \App\Models\Subtask::create([
            'task_id' => $id,
            'title' => $request->title
        ]);

        // 再度、詳細画面に戻る
        return redirect()->route('tasks.show', $id);
    }



    // 詳細タスク（サブタスク）を削除する（★ここを追加！）
    public function destroySubtask($id)
    {
        // 削除したいサブタスクをDBから探す
        $subtask = \App\Models\Subtask::findOrFail($id);

        // 後で元の詳細画面に戻るために、親タスクのIDを一時的にキープしておく
        $task_id = $subtask->task_id;

        // サブタスクを削除
        $subtask->delete();

        // 削除が終わったら、元の親タスクの詳細画面に戻る
        return redirect()->route('tasks.show', $task_id);
    }

    // 詳細タスク（サブタスク）を更新する（★ここを追加！）
    public function updateSubtask(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|max:255'
        ]);

        // 編集したいサブタスクをDBから探す
        $subtask = \App\Models\Subtask::findOrFail($id);

        // タイトルを上書きして保存
        $subtask->title = $request->title;
        $subtask->save();

        // 元の親タスクの詳細画面に戻る
        return redirect()->route('tasks.show', $subtask->task_id);
    }
}
