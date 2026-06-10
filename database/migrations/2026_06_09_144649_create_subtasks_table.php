<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subtasks', function (Blueprint $table) {
            $table->id();
            // どの親タスクに紐づいているか（外部キー）
            // 親タスクが消えたら、連動してこのサブタスクも消える設定（onDelete('cascade')）
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            // 細かいタスクの内容
            $table->string('title');
            // 完了したかどうかのフラグ（デフォルトは未完了）
            $table->boolean('is_completed')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subtasks');
    }
};
