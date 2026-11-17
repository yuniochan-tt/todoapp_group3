@extends('layouts.app')

@section('content')
<h1 class="h3 mb-4">TODOリスト</h1>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('todos.store') }}" method="POST" class="d-flex gap-2 mb-4">
    @csrf
    <input type="text" name="task" class="form-control" placeholder="やることを入力"
           value="{{ old('task') }}" maxlength="255" required>
    <input type="date" name="due_date" class="form-control" style="max-width: 11rem;"
           value="{{ old('due_date') }}">
    <button type="submit" class="btn btn-primary text-nowrap">追加</button>
</form>

<h2 class="h5">やること一覧</h2>
<ul class="nav nav-pills mb-3">
    <li class="nav-item">
        <a class="nav-link {{ $filter === 'all' ? 'active' : '' }}"
           href="{{ route('todos.index') }}">すべて</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $filter === 'active' ? 'active' : '' }}"
           href="{{ route('todos.index', ['filter' => 'active']) }}">未完了</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $filter === 'completed' ? 'active' : '' }}"
           href="{{ route('todos.index', ['filter' => 'completed']) }}">完了済み</a>
    </li>
</ul>

<ul class="list-group">
    @foreach ($todos as $todo)
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <span class="{{ $todo->is_completed ? 'text-decoration-line-through text-muted' : '' }}">
                {{ $todo->task }}
                @if ($todo->due_date)
                    <small class="text-muted">（期限：{{ $todo->due_date->format('Y/m/d') }}）</small>
                @endif
            </span>

            <div class="d-flex gap-1">
                <form action="{{ route('todos.toggle', $todo) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-sm btn-outline-success">
                        {{ $todo->is_completed ? '未完了に戻す' : '完了' }}
                    </button>
                </form>

                <a href="{{ route('todos.edit', $todo) }}" class="btn btn-sm btn-outline-secondary">編集</a>

                <form action="{{ route('todos.destroy', $todo) }}" method="POST"
                      onsubmit="return confirm('本当に削除しますか？');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger">削除</button>
                </form>
            </div>
        </li>
    @endforeach
</ul>
@endsection