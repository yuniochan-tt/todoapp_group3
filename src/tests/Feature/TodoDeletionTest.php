<?php

namespace Tests\Feature;

use App\Models\Todo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoDeletionTest extends TestCase
{
    use RefreshDatabase;

    public function test_todo_list_displays_a_delete_form_for_each_todo(): void
    {
        $todo = Todo::create(['task' => '削除するTODO']);

        $this->get(route('todos.index'))
            ->assertOk()
            ->assertSee('action="'.route('todos.destroy', $todo).'"', false)
            ->assertSee('name="_method" value="DELETE"', false)
            ->assertSee('本当に削除しますか？', false);
    }

    public function test_deleting_a_todo_removes_only_that_todo(): void
    {
        $todoToDelete = Todo::create(['task' => '削除するTODO']);
        $todoToKeep = Todo::create(['task' => '残すTODO']);

        $this->delete(route('todos.destroy', $todoToDelete))
            ->assertRedirect(route('todos.index'));

        $this->assertDatabaseMissing('todos', ['id' => $todoToDelete->id]);
        $this->assertDatabaseHas('todos', [
            'id' => $todoToKeep->id,
            'task' => '残すTODO',
        ]);
    }

    public function test_deleting_a_nonexistent_todo_returns_not_found(): void
    {
        $this->delete(route('todos.destroy', 999))
            ->assertNotFound();
    }
}
