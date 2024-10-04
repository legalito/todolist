<div class="todo-container">
    <header class="todo-header">
        <h1 class="app-title">To-Do App</h1>
        <div class="app-icons">
            <i class="icon-bell"></i>
            <i class="icon-settings"></i>
        </div>
    </header>
    <div class="todo-main">
        <div class="todo-board">
            @foreach($categories as $category)
            <div class="todo-column" data-category="{{ $category }}">
                <h2 class="column-title">{{ ucfirst($category) }}</h2>
                <ul class="todo-items" data-category="{{ $category }}">
                    @foreach ($todos as $index => $todo)
                            @if ($todo['category'] === $category)
                            <li class="todo-item" data-id="{{ $index }}">                                    <span class="todo-text">{{ $todo['text'] }}</span>
                                    <div class="todo-label" wire:click="toggleLabelEdit({{ $todo['id'] }})">
                                        @if($editingLabel && $currentEditingTodoId === $todo['id'])
                                            <select wire:change="updateLabel({{ $todo['id'] }}, $event.target.value)" wire:click.stop>
                                                <option value="Aucune" {{ $todo['label'] === 'Aucune' ? 'selected' : '' }}>Aucune</option>
                                                @foreach($labels as $label)
                                                    <option value="{{ $label }}" {{ $todo['label'] === $label ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @else
                                            <span class="label-display">{{ $todo['label'] }}</span>
                                        @endif
                                    </div>
                                    <div class="todo-due-date" wire:click="toggleDueDateEdit({{ $todo['id'] }})">

                                    </div>
                                    <div class="todo-actions">
                                        <button wire:click="editTodo({{ $todo['id'] }})" class="todo-edit">✎</button>
                                        <button wire:click="removeTodo({{ $todo['id'] }})" class="todo-delete">✕</button>
                                     </div>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                    <div class="todo-form">
                        <input type="text" wire:model="newTodos.{{ $category }}" placeholder="Nouvelle tâche" class="todo-input">
                        <button wire:click="addTodo('{{ $category }}')" class="todo-button">+</button>
                    </div>
                </div>
            @endforeach
        </div>
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
        <script>
          document.addEventListener('livewire:load', function () {
            initSortable();
          });

          function initSortable() {
            var columns = document.querySelectorAll('.todo-items');
            columns.forEach(function(column) {
              new Sortable(column, {
                group: 'shared',
                animation: 150,
                onEnd: function (evt) {
                  console.log('Item moved:', evt.item);
                  console.log('To category:', evt.to.dataset.category);
                  var itemEl = evt.item;
                  var toCategory = evt.to.dataset.category;
                  var todoId = itemEl.dataset.id;
                  Livewire.emit('todoMoved', todoId, toCategory);
                }
              });
            });
          }

          // Réinitialiser Sortable après chaque mise à jour Livewire
        </script>
    </div>

    <!-- Modal d'édition -->
    @if($editingTodo !== null)
    <div class="modal" style="display: flex;">
        <div class="modal-content">
            <h2>Modifier la tâche</h2>
            <input type="text" wire:model="editingTodoText" class="modal-input">
            <select wire:model="editingTodoCategory" class="modal-select">
                @foreach($categories as $category)
                    <option value="{{ $category }}">{{ ucfirst($category) }}</option>
                @endforeach
            </select>
            <input type="date" wire:model="editingDueDate" class="modal-input">
            <div class="modal-actions">
                <button wire:click="updateTodo" class="modal-button">Sauvegarder</button>
                <button wire:click="cancelEdit" class="modal-close">Annuler</button>
            </div>
        </div>
    </div>
    @endif

    <style>
        /* Structure générale */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            color: #333;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Header */
        .todo-header {
            display: flex;
            justify-content: space-between;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .app-title {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }

        .app-icons i {
            font-size: 18px;
            margin-left: 20px;
            cursor: pointer;
        }

        /* Sidebar */
        .todo-sidebar {
            width: 200px;
            padding: 20px;
            background-color: #fff;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.05);
        }

        .todo-categories li {
            list-style: none;
            padding: 10px;
            font-size: 16px;
            color: #555;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .todo-categories li:hover {
            background-color: #f1f1f1;
            border-radius: 8px;
        }

        /* Contenu principal */
        .todo-content {
            padding: 20px;
            flex-grow: 1;
        }

        .todo-title {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        /* Formulaire d'ajout de tâche */
        .todo-form {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .todo-input {
            flex-grow: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
        }

        .todo-button {
            background-color: #5bc0de;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .todo-button:hover {
            background-color: #31b0d5;
        }

        /* Liste des tâches */
        .todo-items {
            list-style: none;
            padding: 0;
        }

        .todo-item {
            display: flex;
            align-items: center;
            padding: 10px;
            background-color: white;
            margin-bottom: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            transition: background-color 0.3s ease;
        }

        .todo-item:hover {
            background-color: #f9f9f9;
        }

        .todo-checkbox {
            margin-right: 10px;
            transform: scale(1.5);
        }

        .todo-text {
            flex-grow: 1;
            font-size: 16px;
            color: #333;
        }

        .todo-delete {
            background: none;
            border: none;
            color: red;
            font-size: 18px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .todo-delete:hover {
            transform: scale(1.2);
        }

        /* Bouton flottant */
        .floating-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #5bc0de;
            color: white;
            border: none;
            font-size: 24px;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .floating-button:hover {
            background-color: #31b0d5;
        }

        /* Modale */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        .modal-input,
        .modal-textarea,
        .modal-date {
            width: 100%;
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
        }

        .modal-button {
            background-color: #5cb85c;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
        }

        .modal-close {
            background-color: #d9534f;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
        }

        /* Animation */
        @keyframes crossOut {
            from {
                width: 0;
            }

            to {
                width: 100%;
            }
        }

        /* Nouveau style pour le board Trello-like */
        .todo-board {
            display: flex;
            gap: 20px;
            padding: 20px;
            overflow-x: auto;
        }

        .todo-column {
            background-color: #f4f5f7;
            border-radius: 8px;
            min-width: 300px;
            max-width: 300px;
            padding: 10px;
        }

        .column-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #e4e6e9;
            border-radius: 8px;
        }

        .todo-items {
            min-height: 100px;
        }

        .todo-item {
            background-color: white;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12);
        }

        .todo-form {
            display: flex;
            margin-top: 10px;
        }

        .todo-input {
            flex-grow: 1;
            margin-right: 5px;
        }

        .todo-button {
            padding: 5px 10px;
        }

        /* Styles pour les actions de la tâche */
        .todo-actions {
            display: flex;
            gap: 5px;
        }

        .todo-edit,
        .todo-delete {
            background: none;
            border: none;
            font-size: 16px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .todo-edit:hover,
        .todo-delete:hover {
            transform: scale(1.2);
        }

        .todo-edit {
            color: #4a90e2;
        }

        /* Styles pour la modal */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            width: 300px;
        }

        .modal-input,
        .modal-select {
            width: 100%;
            margin-bottom: 10px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .modal-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
        }

        .modal-button,
        .modal-close {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .modal-button {
            background-color: #4a90e2;
            color: white;
        }

        .modal-close {
            background-color: #e2e2e2;
            color: #333;
        }
        .todo-item {
       cursor: move;
     }
     .todo-item {
       cursor: move;
       user-select: none;
     }
     .sortable-ghost {
       opacity: 0.5;
       background: #c8ebfb;
     }
     .todo-label {
         margin-left: 10px;
         padding: 2px 5px;
         border-radius: 3px;
         font-size: 12px;
         cursor: pointer;
     }

     .label-display {
         background-color: #e0e0e0;
         padding: 2px 5px;
         border-radius: 3px;
     }

     .todo-label select {
         padding: 2px;
         font-size: 12px;
     }
     .todo-due-date {
         margin-left: 10px;
         font-size: 12px;
         cursor: pointer;
     }

     .due-date-display {
         background-color: #e0e0e0;
         padding: 2px 5px;
         border-radius: 3px;
     }

     .todo-due-date input[type="date"] {
         padding: 2px;
         font-size: 12px;
     }
    </style>
</div>
