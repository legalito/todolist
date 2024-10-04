<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Todo;

class Calendar extends Component
{
    public $events = [];

    public function mount()
    {
        $this->loadEvents();
    }

    public function loadEvents()
    {
        $this->events = Todo::all()->map(function ($todo) {
            return [
                'id' => $todo->id,
                'title' => $todo->text,
                'start' => $todo->due_date ? $todo->due_date->format('Y-m-d') : null,
                'label' => $todo->label,
                'end' => $todo->due_date ? $todo->due_date->addDay()->format('Y-m-d') : null,
                'backgroundColor' => $this->getCategoryColor($todo->category),
            ];
        })->filter(function ($event) {
            return $event['start'] !== null;
        })->values()->toArray();

    }

    private function getCategoryColor($category)
    {
        $colors = [
            'to-do' => '#ffa500',
            'in-progress' => '#4169e1',
            'completed' => '#32cd32',
        ];

        return $colors[$category] ?? '#808080';
    }

    public function render()
    {
        return view('livewire.calendar');
    }
}
