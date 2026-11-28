<?php

namespace App\Http\Controllers\Types;

use App\Http\Controllers\Controller;
use App\Http\Requests\Types\StoreTypeRequest;
use App\Http\Requests\Types\UpdateTypeRequest;
use App\Models\Type;
use App\Repositories\Contracts\TypeRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TypeController extends Controller
{
    public function __construct(
        private readonly TypeRepository $types,
    ) {
        //
    }

    public function index(Request $request): Response
    {
        $search = $request->string('search')->toString();

        return Inertia::render('types/index', [
            'types' => $this->types->paginate($search !== '' ? $search : null),
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    public function store(StoreTypeRequest $request): RedirectResponse
    {
        $this->types->create($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Type created.')]);

        return back();
    }

    public function update(UpdateTypeRequest $request, Type $type): RedirectResponse
    {
        $this->types->update($type, $request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Type updated.')]);

        return back();
    }

    public function destroy(Type $type): RedirectResponse
    {
        $this->types->delete($type);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Type deleted.')]);

        return back();
    }
}
