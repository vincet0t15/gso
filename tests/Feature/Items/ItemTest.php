<?php

use App\Models\Item;
use App\Models\PpeClassification;
use App\Models\Type;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;

function itemUser(array $permissions = ['items.view']): User
{
    $permissions = array_values(array_unique(['administration.view', ...$permissions]));

    foreach ($permissions as $permission) {
        Permission::findOrCreate($permission, 'web');
    }

    $user = User::factory()->create();
    $user->givePermissionTo($permissions);

    return $user;
}

test('guests are redirected to the login page', function () {
    $response = $this->get(route('items.index'));

    $response->assertRedirect(route('login'));
});

test('accounts without the administration gate cannot list items', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('items.index'))
        ->assertForbidden();
});

test('accounts with the view permission can list items', function () {
    Item::factory()->create(['item_name' => 'Bond Paper A4']);

    $this->actingAs(itemUser())
        ->get(route('items.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('items/index')
            ->has('items.data', 1)
            ->where('items.data.0.item_name', 'Bond Paper A4')
        );
});

test('items can be searched by code, name or description', function () {
    Item::factory()->create([
        'item_code' => 'ITM-0001',
        'item_name' => 'Bond Paper A4',
        'description' => 'White paper',
    ]);
    Item::factory()->create([
        'item_code' => 'ITM-0002',
        'item_name' => 'Ballpen',
        'description' => 'Blue ink',
    ]);

    $this->actingAs(itemUser())
        ->get(route('items.index', ['search' => 'Ballpen']))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->has('items.data', 1)
            ->where('items.data.0.item_name', 'Ballpen')
        );
});

test('an item can be created', function () {
    $type = Type::factory()->create();
    $ppe = PpeClassification::factory()->create();
    $unit = Unit::factory()->create();

    $this->actingAs(itemUser(['items.create']))
        ->post(route('items.store'), [
            'item_code' => 'ITM-0001',
            'item_name' => 'Bond Paper A4',
            'description' => 'White paper',
            'type_id' => $type->id,
            'ppe_classification_id' => $ppe->id,
            'unit_id' => $unit->id,
            'unit_cost' => 150.50,
            'reorder_point' => 5,
            'is_active' => true,
        ])
        ->assertRedirect();

    $item = Item::query()->where('item_code', 'ITM-0001')->sole();

    expect($item->item_name)->toBe('Bond Paper A4')
        ->and($item->type_id)->toBe($type->id)
        ->and($item->ppe_classification_id)->toBe($ppe->id)
        ->and($item->unit_id)->toBe($unit->id);
});

test('creating an item requires code and name', function () {
    $this->actingAs(itemUser(['items.create']))
        ->post(route('items.store'), [
            'item_code' => '',
            'item_name' => '',
        ])
        ->assertSessionHasErrors(['item_code', 'item_name']);

    expect(Item::query()->count())->toBe(0);
});

test('item codes must be unique', function () {
    Item::factory()->create(['item_code' => 'ITM-0001']);

    $this->actingAs(itemUser(['items.create']))
        ->post(route('items.store'), [
            'item_code' => 'ITM-0001',
            'item_name' => 'Another',
        ])
        ->assertSessionHasErrors('item_code');

    expect(Item::query()->count())->toBe(1);
});

test('creating an item requires the create permission', function () {
    $this->actingAs(itemUser())
        ->post(route('items.store'), [
            'item_code' => 'ITM-0001',
            'item_name' => 'Bond Paper',
        ])
        ->assertForbidden();

    expect(Item::query()->count())->toBe(0);
});

test('foreign keys must reference existing records', function () {
    $this->actingAs(itemUser(['items.create']))
        ->post(route('items.store'), [
            'item_code' => 'ITM-0001',
            'item_name' => 'Bond Paper',
            'type_id' => 99999,
            'ppe_classification_id' => 99999,
            'unit_id' => 99999,
        ])
        ->assertSessionHasErrors(['type_id', 'ppe_classification_id', 'unit_id']);
});

test('an item can be updated', function () {
    $item = Item::factory()->create([
        'item_code' => 'ITM-0001',
        'item_name' => 'Old Name',
    ]);

    $this->actingAs(itemUser(['items.update']))
        ->put(route('items.update', $item), [
            'item_code' => 'ITM-0001',
            'item_name' => 'New Name',
            'unit_cost' => 200.00,
        ])
        ->assertRedirect();

    $item->refresh();

    expect($item->item_name)->toBe('New Name')
        ->and((float) $item->unit_cost)->toBe(200.00);
});

test('an item may keep its own code when updated', function () {
    $item = Item::factory()->create([
        'item_code' => 'ITM-0001',
        'item_name' => 'Bond Paper',
    ]);

    $this->actingAs(itemUser(['items.update']))
        ->put(route('items.update', $item), [
            'item_code' => 'ITM-0001',
            'item_name' => 'Bond Paper A4',
        ])
        ->assertSessionHasNoErrors();

    expect($item->refresh()->item_name)->toBe('Bond Paper A4');
});

test('an item cannot take another item code on update', function () {
    Item::factory()->create(['item_code' => 'ITM-0001']);
    $item = Item::factory()->create(['item_code' => 'ITM-0002']);

    $this->actingAs(itemUser(['items.update']))
        ->put(route('items.update', $item), [
            'item_code' => 'ITM-0001',
            'item_name' => 'Changed',
        ])
        ->assertSessionHasErrors('item_code');

    expect($item->refresh()->item_code)->toBe('ITM-0002');
});

test('updating an item requires the update permission', function () {
    $item = Item::factory()->create(['item_name' => 'Bond Paper']);

    $this->actingAs(itemUser())
        ->put(route('items.update', $item), [
            'item_code' => $item->item_code,
            'item_name' => 'Changed',
        ])
        ->assertForbidden();

    expect($item->refresh()->item_name)->toBe('Bond Paper');
});

test('an item can be created with an image', function () {
    Storage::fake('public');

    $this->actingAs(itemUser(['items.create']))
        ->post(route('items.store'), [
            'item_code' => 'ITM-IMG',
            'item_name' => 'Item With Image',
            'image' => UploadedFile::fake()->image('item.jpg'),
        ])
        ->assertRedirect();

    $item = Item::query()->where('item_code', 'ITM-IMG')->sole();

    expect($item->image)->not->toBeNull();
    Storage::disk('public')->assertExists($item->image);
});

test('an item image can be removed on update', function () {
    Storage::fake('public');

    $item = Item::factory()->create(['image' => UploadedFile::fake()->image('old.jpg')->store('items', 'public')]);

    $this->actingAs(itemUser(['items.update']))
        ->put(route('items.update', $item), [
            'item_code' => $item->item_code,
            'item_name' => $item->item_name,
            'remove_image' => true,
        ])
        ->assertRedirect();

    expect($item->fresh()->image)->toBeNull();
});

test('an item can be deleted', function () {
    $item = Item::factory()->create();

    $this->actingAs(itemUser(['items.delete']))
        ->delete(route('items.destroy', $item))
        ->assertRedirect();

    $this->assertModelMissing($item);
});

test('deleting an item requires the delete permission', function () {
    $item = Item::factory()->create();

    $this->actingAs(itemUser())
        ->delete(route('items.destroy', $item))
        ->assertForbidden();

    $this->assertModelExists($item);
});
