<?php

namespace App\Http\Controllers\PpeClassifications;

use App\Http\Controllers\Controller;
use App\Http\Requests\PpeClassifications\StorePpeClassificationRequest;
use App\Http\Requests\PpeClassifications\UpdatePpeClassificationRequest;
use App\Models\PpeClassification;
use App\Repositories\Contracts\PpeClassificationRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PpeClassificationController extends Controller
{
    public function __construct(
        private readonly PpeClassificationRepository $ppeClassifications,
    ) {
        //
    }

    public function index(Request $request): Response
    {
        $search = $request->string('search')->toString();

        return Inertia::render('ppe-classifications/index', [
            'ppeClassifications' => $this->ppeClassifications->paginate($search !== '' ? $search : null),
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    public function store(StorePpeClassificationRequest $request): RedirectResponse
    {
        $this->ppeClassifications->create($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('PPE classification created.')]);

        return back();
    }

    public function update(UpdatePpeClassificationRequest $request, PpeClassification $ppeClassification): RedirectResponse
    {
        $this->ppeClassifications->update($ppeClassification, $request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => __('PPE classification updated.')]);

        return back();
    }

    public function destroy(PpeClassification $ppeClassification): RedirectResponse
    {
        $this->ppeClassifications->delete($ppeClassification);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('PPE classification deleted.')]);

        return back();
    }
}
