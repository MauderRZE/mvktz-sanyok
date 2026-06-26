<?php

namespace App\Http\Livewire\Admin;

use App\Models\SystemError;
use Livewire\Component;
use Livewire\WithPagination;

class SystemErrorManager extends Component
{
    use WithPagination;

    public $isOpen = false;
    public $pageType;
    public $errorType;
    public $errorText;
    public $isResolved = false;

    public $editingId = null;

    protected $rules = [
        'pageType' => 'required|string|max:255',
        'errorType' => 'required|string|max:255',
        'errorText' => 'required|string',
    ];

    public function getAvailablePagesProperty()
    {
        return [
            'equipment' => 'Обладнання',
            'components' => 'Комплектуючі',
            'software-licenses' => 'Ліцензії ПЗ',
            'low-value-materials' => 'Малоцінні матеріали',
            'employees' => 'Співробітники',
            'movements' => 'Переміщення',
            'complaints' => 'Скарги та інциденти',
            'maintenance-logs' => 'Журнал ТО',
            'contracts' => 'Договори',
            'suppliers' => 'Постачальники',
            'categories' => 'Категорії',
            'types' => 'Типи техніки',
            'base-components' => 'Базові компоненти',
            'base-materials' => 'Базові матеріали',
            'locations' => 'Кабінети та локації',
            'maintenance-types' => 'Типи обслуговування',
            'type-requirements' => 'Шаблони типів',
            'users' => 'Адміністратори',
            'system-errors' => 'Виправлення помилок',
        ];
    }

    public function getLaravelErrorTypesProperty()
    {
        return [
            'QueryException' => 'QueryException (Помилка бази даних)',
            'ValidationException' => 'ValidationException (Помилка валідації)',
            'NotFoundHttpException' => 'NotFoundHttpException (Сторінку не знайдено 404)',
            'ModelNotFoundException' => 'ModelNotFoundException (Запис не знайдено)',
            'AuthenticationException' => 'AuthenticationException (Помилка авторизації 401)',
            'AuthorizationException' => 'AuthorizationException (Помилка доступу 403)',
            'TokenMismatchException' => 'TokenMismatchException (Помилка CSRF токена 419)',
            'MethodNotAllowedHttpException' => 'MethodNotAllowedHttpException (Метод не підтримується 405)',
            'BadMethodCallException' => 'BadMethodCallException (Виклик неіснуючого методу)',
            'ErrorException' => 'ErrorException (Стандартна помилка PHP)',
            'ParseError' => 'ParseError (Синтаксична помилка)',
            'TypeError' => 'TypeError (Помилка типу даних)',
            'Exception' => 'Exception (Інша помилка)',
        ];
    }

    public function mount()
    {
        // Доступ дозволено всім авторизованим користувачам (маршрут вже має middleware auth)
    }

    public function resetFields()
    {
        $this->pageType = '';
        $this->errorType = '';
        $this->errorText = '';
        $this->isResolved = false;
        $this->editingId = null;
    }

    public function create()
    {
        $this->resetFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetFields();
    }

    public function store()
    {
        $this->validate();

        if ($this->editingId) {
            $error = SystemError::findOrFail($this->editingId);
            $error->update([
                'page_type' => $this->pageType,
                'error_type' => $this->errorType,
                'error_text' => $this->errorText,
                'is_resolved' => $this->isResolved,
            ]);
        } else {
            SystemError::create([
                'page_type' => $this->pageType,
                'error_type' => $this->errorType,
                'error_text' => $this->errorText,
                'is_resolved' => $this->isResolved,
            ]);
        }

        $this->closeModal();
    }

    public function edit($id)
    {
        $error = SystemError::findOrFail($id);
        $this->editingId = $error->id;
        $this->pageType = $error->page_type;
        $this->errorType = $error->error_type;
        $this->errorText = $error->error_text;
        $this->isResolved = $error->is_resolved;
        
        $this->openModal();
    }

    public function toggleResolved($id)
    {
        $error = SystemError::findOrFail($id);
        $error->update([
            'is_resolved' => !$error->is_resolved,
        ]);
    }

    public function delete($id)
    {
        SystemError::findOrFail($id)->delete();
    }

    public function render()
    {
        $errorsList = SystemError::orderBy('created_at', 'desc')->paginate(10);
        return view('livewire.admin.system-error-manager', [
            'errorsList' => $errorsList,
        ])->layout('layouts.admin');
    }
}
