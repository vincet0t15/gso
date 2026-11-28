<?php

namespace App\Http\Controllers\Items;

use App\Http\Controllers\Controller;
use App\Http\Requests\Items\StoreItemRequest;
use App\Http\Requests\Items\UpdateItemRequest;
use App\Models\Item;
use App\Models\PpeClassification;
use App\Models\Type;
use App\Models\Unit;
use App\Repositories\Contracts\ItemRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ItemController extends Controller
{
    public function __construct(
        private readonly ItemRepository $items,
    ) {
        //
    }

    public function index(Request $request): Response
    {
        $search = $request->string('search')->toString();

        return Inertia::render('items/index', [
            'items' => $this->items->paginate($search !== '' ? $search : null),
            'filters' => [
                'search' => $search,
            ],
            'types' => Type::query()->orderBy('name')->get(['id', 'name']),
            'ppeClassifications' => PpeClassification::query()->orderBy('name')->get(['id', 'name']),
            'units' => Unit::query()->orderBy('name')->get(['id', 'name', 'code']),
        ]);
    }

    public function store(StoreItemRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('items', 'public');
        }

        $this->items->create($data);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Item created.')]);

        return back();
    }

    public function update(UpdateItemRequest $request, Item $item): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }
            $data['image'] = $request->file('image')->store('items', 'public');
        } elseif ($request->boolean('remove_image')) {
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }
            $data['image'] = null;
        } else {
            unset($data['image']);
        }

        $this->items->update($item, $data);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Item updated.')]);

        return back();
    }

    public function destroy(Item $item): RedirectResponse
    {
        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }

        $this->items->delete($item);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Item deleted.')]);

        return back();
    }
}
