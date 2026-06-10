<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    // 1つのタスクは、たくさんのサブタスクを持つ
    public function subtasks()
    {
        return $this->hasMany(Subtask::class);
    }
}
