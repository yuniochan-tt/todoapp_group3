<?php

namespace App\Http\Controllers;

use App\Http\Requests\TodoRequest;
use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    /**
     * TODO一覧の表示（絞り込み対応）
     */
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'all');

        $todos = Todo::query()
            ->when($filter === 'active', fn ($query) => $query->where('is_completed', false))
            ->when($filter === 'completed', fn ($query) => $query->where('is_completed', true))
            ->orderByRaw('due_date IS NULL')
            ->orderBy('due_date')
            ->latest()
            ->get();

        return view('todos.index', compact('todos', 'filter'));
    }

    /**
     * TODOの追加
     */
    public function store(TodoRequest $request)
    {
        Todo::create($request->validated());

        return redirect()->route('todos.index');
    }

    /**
     * 編集画面の表示
     */
    public function edit(Todo $todo)
    {
        return view('todos.edit', compact('todo'));
    }

    /**
     * TODOの更新
     */
    public function update(TodoRequest $request, Todo $todo)
    {
        $todo->update($request->validated());

        return redirect()->route('todos.index');
    }

    /**
     * TODOの削除
     */
    public function destroy(Todo $todo)
    {
        $todo->delete();

        return back();
    }

    /**
     * 完了／未完了の切り替え
     */
    public function toggle(Todo $todo)
    {
        $todo->update([
            'is_completed' => ! $todo->is_completed,
        ]);

        return back();
    }
}