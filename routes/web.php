<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

// 1. 画面を普通に開いたとき（GET）は一覧を表示する
Route::get('/', [TaskController::class, 'index'])->name('tasks.index');

// 2. フォームから送信されたとき（POST）はデータをDBに保存する
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');

// タスクを削除する（DELETEメソッド、URLに{id}を含める）
Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');
// 1. 編集画面を表示する（GET）
Route::get('/tasks/{id}/edit', [TaskController::class, 'edit'])->name('tasks.edit');

// 2. 編集されたデータを更新する（PATCH）
Route::patch('/tasks/{id}', [TaskController::class, 'update'])->name('tasks.update');

// 1. 詳細画面を表示する（GET）
Route::get('/tasks/{id}', [TaskController::class, 'show'])->name('tasks.show');

// 2. 詳細画面でメモを保存・更新する（PATCH）
Route::patch('/tasks/{id}/description', [TaskController::class, 'updateDescription'])->name('tasks.updateDescription');

// 詳細画面（show）の中でサブタスクを追加する
Route::post('/tasks/{id}/subtasks', [TaskController::class, 'storeSubtask'])->name('subtasks.store');

// 詳細タスク（サブタスク）を削除する
Route::delete('/subtasks/{id}', [TaskController::class, 'destroySubtask'])->name('subtasks.destroy');

// 詳細タスク（サブタスク）を更新する（PATCH）
Route::patch('/subtasks/{id}', [TaskController::class, 'updateSubtask'])->name('subtasks.update');