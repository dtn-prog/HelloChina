<?php

namespace App\Support\Traits;

use App\Support\Contracts\CrudResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasCrud
{
    protected ?CrudResource $resource = null;

    abstract protected function getResource(): CrudResource;

    public function index(\Illuminate\Http\Request $request)
    {
        $resource = $this->getResource();
        $modelClass = $resource->model();

        $query = $modelClass::query();

        // Eager load relationships
        if (!empty($resource->with())) {
            $query->with($resource->with());
        }

        // Apply searchable
        if ($search = $request->input('search')) {
            $searchable = $resource->searchable();
            $query->where(function ($q) use ($search, $searchable) {
                foreach ($searchable as $field) {
                    $q->orWhere($field, 'LIKE', "%{$search}%");
                }
            });
        }

        // Apply filters
        foreach ($resource->filters() as $filter) {
            $value = $request->input($filter['field']);
            if ($value !== null && $value !== '') {
                match ($filter['type'] ?? 'text') {
                    'select' => $query->where($filter['field'], $value),
                    'date' => $query->whereDate($filter['field'], $value),
                    'date_range' => $query->whereBetween($filter['field'], [
                        $request->input($filter['field'] . '_from'),
                        $request->input($filter['field'] . '_to'),
                    ]),
                    'boolean' => $query->where($filter['field'], (bool) $value),
                    default => $query->where($filter['field'], 'LIKE', "%{$value}%"),
                };
            }
        }

        // Apply sortable
        if ($sort = $request->input('sort')) {
            $direction = $request->input('direction', 'asc');
            if (in_array($sort, $resource->sortable())) {
                $query->orderBy($sort, $direction);
            }
        } else {
            $query->latest();
        }

        // Custom query
        $query = $resource->query($query);

        $perPage = $request->input('per_page', 15);
        $items = $query->paginate($perPage);

        if ($request->expectsJson()) {
            return response()->json(['data' => $items]);
        }

        return view($resource->viewDir() . '.index', [
            'items' => $items,
            'resource' => $resource,
        ]);
    }

    public function create(\Illuminate\Http\Request $request)
    {
        $resource = $this->getResource();

        if ($request->expectsJson()) {
            return response()->json(['data' => ['fields' => $resource->fields()]]);
        }

        return view($resource->viewDir() . '.create', [
            'resource' => $resource,
        ]);
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $resource = $this->getResource();
        $request->validate($resource->rules());

        $modelClass = $resource->model();
        $item = $modelClass::create($request->only(
            collect($resource->fields())->pluck('field')->toArray()
        ));

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'data' => $item], 201);
        }

        return redirect()
            ->route($resource->routePrefix() . '.show', $item)
            ->with('success', $resource->title() . ' created successfully.');
    }

    public function show(\Illuminate\Http\Request $request, $id)
    {
        $resource = $this->getResource();
        $modelClass = $resource->model();
        $item = $modelClass::findOrFail($id);

        if (!empty($resource->with())) {
            $item->load($resource->with());
        }

        if ($request->expectsJson()) {
            return response()->json(['data' => $item]);
        }

        return view($resource->viewDir() . '.show', [
            'item' => $item,
            'resource' => $resource,
        ]);
    }

    public function edit(\Illuminate\Http\Request $request, $id)
    {
        $resource = $this->getResource();
        $modelClass = $resource->model();
        $item = $modelClass::findOrFail($id);

        if ($request->expectsJson()) {
            return response()->json(['data' => $item, 'fields' => $resource->fields()]);
        }

        return view($resource->viewDir() . '.edit', [
            'item' => $item,
            'resource' => $resource,
        ]);
    }

    public function update(\Illuminate\Http\Request $request, $id)
    {
        $resource = $this->getResource();
        $modelClass = $resource->model();
        $item = $modelClass::findOrFail($id);

        $request->validate($resource->updateRules());

        $item->update($request->only(
            collect($resource->fields())->pluck('field')->toArray()
        ));

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'data' => $item]);
        }

        return redirect()
            ->route($resource->routePrefix() . '.show', $item)
            ->with('success', $resource->title() . ' updated successfully.');
    }

    public function destroy(\Illuminate\Http\Request $request, $id)
    {
        $resource = $this->getResource();
        $modelClass = $resource->model();
        $item = $modelClass::findOrFail($id);

        $item->delete();

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()
            ->route($resource->routePrefix() . '.index')
            ->with('success', $resource->title() . ' deleted successfully.');
    }

    public function export(\Illuminate\Http\Request $request)
    {
        $resource = $this->getResource();
        $modelClass = $resource->model();

        $query = $modelClass::query();

        // Apply same filters as index
        if ($search = $request->input('search')) {
            $searchable = $resource->searchable();
            $query->where(function ($q) use ($search, $searchable) {
                foreach ($searchable as $field) {
                    $q->orWhere($field, 'LIKE', "%{$search}%");
                }
            });
        }

        $exportColumns = $resource->exportColumns();
        $filename = Str::plural(strtolower($resource->title())) . '_export_' . now()->format('Y-m-d_His') . '.csv';

        return response()->stream(function () use ($query, $exportColumns, $filename) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, array_values($exportColumns));

            $query->each(function ($row) use ($handle, $exportColumns) {
                $data = [];
                foreach ($exportColumns as $key => $label) {
                    $keys = explode('.', $key);
                    $value = $row;
                    foreach ($keys as $k) {
                        $value = is_object($value) ? $value->{$k} ?? null : ($value[$k] ?? null);
                    }
                    $data[] = $value;
                }
                fputcsv($handle, $data);
            });

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function bulkAction(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,activate,deactivate',
            'ids' => 'required|array',
        ]);

        $resource = $this->getResource();
        $modelClass = $resource->model();
        $items = $modelClass::whereIn('id', $request->ids)->get();

        foreach ($items as $item) {
            match ($request->action) {
                'delete' => $item->delete(),
                'activate' => method_exists($item, 'update') ? $item->update(['status' => 'active']) : null,
                'deactivate' => method_exists($item, 'update') ? $item->update(['status' => 'inactive']) : null,
            };
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => ucfirst($request->action) . ' applied to ' . $items->count() . ' items.']);
        }

        return back()->with('success', ucfirst($request->action) . ' applied to ' . $items->count() . ' items.');
    }
}
