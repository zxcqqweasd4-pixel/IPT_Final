<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

abstract class ResourceController extends Controller
{
    protected string $modelClass;

    protected string $resource;

    protected string $title;

    protected string $singular;

    protected array $fields = [];

    protected array $columns = [];

    protected array $showFields = [];

    protected array $indexWith = [];

    protected array $showWith = [];

    abstract protected function rules(?string $id = null): array;

    protected function validateRequest(Request $request, ?string $id = null): array
    {
        return $request->validate($this->rules($id));
    }

    protected function selectOptions(): array
    {
        return [];
    }

    public function index(): View
    {
        $model = $this->modelClass;
        $records = $model::query()->with($this->indexWith)->latest()->paginate(10);

        return view('records.index', $this->viewData([
            'records' => $records,
        ]));
    }

    public function create(): View
    {
        return view('records.form', $this->viewData([
            'record' => null,
            'formOptions' => $this->selectOptions(),
            'formAction' => route($this->resource.'.store'),
            'httpMethod' => 'POST',
        ]));
    }

    public function store(Request $request): RedirectResponse
    {
        $model = $this->modelClass;
        $record = $model::create($this->validateRequest($request));

        return redirect()->route($this->resource.'.show', $record)
            ->with('success', $this->singular.' created successfully.');
    }

    public function show(string $id): View
    {
        $model = $this->modelClass;
        $record = $model::query()->with($this->showWith)->findOrFail($id);

        return view('records.show', $this->viewData([
            'record' => $record,
            'relatedLessons' => $record->relationLoaded('lessons') ? $record->lessons : collect(),
        ]));
    }

    public function edit(string $id): View
    {
        $model = $this->modelClass;
        $record = $model::findOrFail($id);

        return view('records.form', $this->viewData([
            'record' => $record,
            'formOptions' => $this->selectOptions(),
            'formAction' => route($this->resource.'.update', $record),
            'httpMethod' => 'PUT',
        ]));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $model = $this->modelClass;
        $record = $model::findOrFail($id);
        $record->update($this->validateRequest($request, $id));

        return redirect()->route($this->resource.'.show', $record)
            ->with('success', $this->singular.' updated successfully.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $model = $this->modelClass;

        try {
            $model::findOrFail($id)->delete();
        } catch (QueryException $exception) {
            $message = strtolower($exception->getMessage());
            $isForeignKeyViolation = in_array((string) $exception->getCode(), ['23503'], true)
                || str_contains($message, 'foreign key constraint failed')
                || str_contains($message, 'a foreign key constraint fails');

            if (! $isForeignKeyViolation) {
                throw $exception;
            }

            return redirect()->route($this->resource.'.index')
                ->with('error', 'This '.$this->singular.' still has lessons and cannot be deleted.');
        }

        return redirect()->route($this->resource.'.index')
            ->with('success', $this->singular.' deleted successfully.');
    }

    protected function viewData(array $extra = []): array
    {
        return array_merge([
            'resource' => $this->resource,
            'title' => $this->title,
            'singular' => $this->singular,
            'fields' => $this->fields,
            'columns' => $this->columns,
            'showFields' => $this->showFields,
        ], $extra);
    }
}
