@extends('layouts.app')

@section('content')
<h1 class="h3 mb-4">TODO編集</h1>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('todos.update', $todo) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label for="task" class="form-label">内容</label>
        <input type="text" id="task" name="task" class="form-control"
               value="{{ old('task', $todo->task) }}" maxlength="255" required>
    </div>

    <div class="mb-3">
        <label for="due_date" class="form-label">期限</label>
        <input type="date" id="due_date" name="due_date" class="form-control"
               value="{{ old('due_date', $todo->due_date?->format('Y-m-d')) }}">
    </div>

    <button type="submit" class="btn btn-primary">更新</button>
    <a href="{{ route('todos.index') }}" class="btn btn-link">キャンセル</a>
</form>
@endsection
