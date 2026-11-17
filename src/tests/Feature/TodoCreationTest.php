<?php

namespace Tests\Feature;

use App\Models\Todo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TodoCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_todo_list_displays_the_creation_form(): void
    {
        $this->get('/todos')
            ->assertOk()
            ->assertSee('name="task"', false)
            ->assertSee('action="'.route('todos.store').'"', false)
            ->assertSee('_token', false);
    }

    public function test_todo_can_be_created_and_is_shown_in_the_list(): void
    {
        $this->post(route('todos.store'), [
            'task' => '英語の宿題をやる',
        ])->assertRedirect(route('todos.index'));

        $this->assertDatabaseHas('todos', [
            'task' => '英語の宿題をやる',
        ]);

        $this->get(route('todos.index'))
            ->assertOk()
            ->assertSee('英語の宿題をやる');
    }

    public function test_todo_task_is_required_and_limited_to_255_characters(): void
    {
        $this->from(route('todos.index'))
            ->post(route('todos.store'), ['task' => ''])
            ->assertRedirect(route('todos.index'))
            ->assertSessionHasErrors('task');

        $this->from(route('todos.index'))
            ->post(route('todos.store'), ['task' => str_repeat('a', 256)])
            ->assertRedirect(route('todos.index'))
            ->assertSessionHasErrors('task');

        $this->assertSame(0, Todo::count());
    }
}
