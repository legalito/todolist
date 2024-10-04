<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Todo;
use Carbon\Carbon;

class TestComponent extends Component
{
    public $todos;
    public $newTodos = [];
    public $categories = ['to-do', 'in-progress', 'completed'];
    public $currentFilter = 'all';
    public $editingTodo = null;
    public $editingTodoText = '';
    public $editingTodoCategory = '';
    public $labels = ['Important', 'Urgent', 'Personnel', 'Travail', 'Loisir'];
    public $editingLabel = false;
    public $currentEditingTodoId = null;

    public function mount()
    {
        $this->todos = Todo::all();
    }

    public function addTodo($category)
    {
        if (!empty($this->newTodos[$category])) {
            Todo::create([
                'text' => $this->newTodos[$category],
                'category' => $category,
                'label' => 'Aucune', // Étiquette par défaut
                'due_date' => $this->newTodoDueDate
            ]);
            $this->newTodos[$category] = '';
            $this->todos = Todo::all();

            // Ajoutez cette ligne pour mettre à jour le calendrier
            $this->emit('refreshCalendar', $this->getEventsProperty());
        }
    }

    public function toggleLabelEdit($todoId)
    {
        $this->editingLabel = !$this->editingLabel;
        $this->currentEditingTodoId = $todoId;
    }

    public function updateLabel($todoId, $newLabel)
    {
        $todo = Todo::find($todoId);
        $todo->update(['label' => $newLabel]);
        $this->editingLabel = false;
        $this->currentEditingTodoId = null;
        $this->todos = Todo::all();
    }

    public function removeTodo($id)
    {
        Todo::destroy($id);
        $this->todos = Todo::all();

        // Ajoutez cette ligne pour mettre à jour le calendrier
        $this->emit('refreshCalendar', $this->getEventsProperty());
    }

    public function setFilter($category)
    {
        $this->currentFilter = $category;
    }

    public function getFilteredTodosProperty()
    {
        if ($this->currentFilter === 'all') {
            return $this->todos;
        }

        return $this->todos->filter(function($todo) {
            return $todo->category === $this->currentFilter;
        });
    }

    public function editTodo($id)
    {
        $todo = Todo::find($id);
        $this->editingTodo = $todo->id;
        $this->editingTodoText = $todo->text;
        $this->editingTodoCategory = $todo->category;
    }

    public function updateTodo()
    {
        $todo = Todo::find($this->editingTodo);
        $todo->update([
            'text' => $this->editingTodoText,
            'category' => $this->editingTodoCategory,
            'due_date' => $this->editingTodoDueDate
        ]);
        $this->cancelEdit();
        $this->todos = Todo::all();

        // Ajoutez cette ligne pour mettre à jour le calendrier
        $this->emit('refreshCalendar', $this->getEventsProperty());
    }

    public function cancelEdit()
    {
        $this->editingTodo = null;
        $this->editingTodoText = '';
        $this->editingTodoCategory = '';
    }

    public function render()
    {
        return view('livewire.test-component');
    }
}
