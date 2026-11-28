<?php

namespace App\Http\Controllers\Units;

use App\Http\Controllers\Controller;
use App\Http\Requests\Units\StoreUnitRequest;
use App\Http\Requests\Units\UpdateUnitRequest;
use App\Models\Unit;
use App\Repositories\Contracts\UnitRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UnitController extends Controller
{
    public function __construct(
        private readonly UnitRepository $units,
    ) {
        //
    }

    public function index(Request $request): Response
    {
        $search = $request->string('search')->toString();

        return Inertia::render('units/index', [
            'units' => $this->units->paginate($search !== '' ? $search : null),
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    public function store(StoreUnitRequest $request): RedirectResponse
    {
        $this->units->create($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Unit created.')]);

        return back();
    }

    public function update(UpdateUnitRequest $request, Unit $unit): RedirectResponse
    {
        $this->units->update($unit, $request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Unit updated.')]);

        return back();
    }

    public function destroy(Unit $unit): RedirectResponse
    {
        $this->units->delete($unit);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Unit deleted.')]);

        return back();
    }
}
