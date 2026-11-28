<?php

use App\Models\PpeClassification;
use Spatie\Permission\Models\Permission;
use App\Models\User;

function ppeUser(array $permissions = ['ppe-classifications.view']): User
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
    $response = $this->get(route('ppe-classifications.index'));

    $response->assertRedirect(route('login'));
});

test('accounts without the administration gate cannot list ppe classifications', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('ppe-classifications.index'))
        ->assertForbidden();
});

test('accounts with the view permission can list ppe classifications', function () {
    PpeClassification::factory()->create(['code' => 'M&E', 'name' => 'Machinery and Equipment']);

    $this->actingAs(ppeUser())
        ->get(route('ppe-classifications.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('ppe-classifications/index')
            ->has('ppeClassifications.data', 1)
            ->where('ppeClassifications.data.0.code', 'M&E')
        );
});

test('ppe classifications can be searched by code, name or description', function () {
    PpeClassification::factory()->create([
        'code' => 'M&E',
        'name' => 'Machinery and Equipment',
        'description' => 'Machines',
    ]);
    PpeClassification::factory()->create([
        'code' => 'TE',
        'name' => 'Transportation Equipment',
        'description' => 'Vehicles',
    ]);

    $this->actingAs(ppeUser())
        ->get(route('ppe-classifications.index', ['search' => 'Vehicles']))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->has('ppeClassifications.data', 1)
            ->where('ppeClassifications.data.0.code', 'TE')
        );
});

test('a ppe classification can be created', function () {
    $this->actingAs(ppeUser(['ppe-classifications.create']))
        ->post(route('ppe-classifications.store'), [
            'code' => 'LAND',
            'name' => 'Land',
            'description' => 'Land and improvements',
            'is_active' => true,
        ])
        ->assertRedirect();

    $item = PpeClassification::query()->where('code', 'LAND')->sole();

    expect($item->name)->toBe('Land')
        ->and($item->is_active)->toBeTrue();
});

test('creating a ppe classification requires code and name', function () {
    $this->actingAs(ppeUser(['ppe-classifications.create']))
        ->post(route('ppe-classifications.store'), [
            'code' => '',
            'name' => '',
        ])
        ->assertSessionHasErrors(['code', 'name']);

    expect(PpeClassification::query()->count())->toBe(0);
});

test('ppe classification codes must be unique', function () {
    PpeClassification::factory()->create(['code' => 'M&E']);

    $this->actingAs(ppeUser(['ppe-classifications.create']))
        ->post(route('ppe-classifications.store'), [
            'code' => 'M&E',
            'name' => 'Another',
        ])
        ->assertSessionHasErrors('code');

    expect(PpeClassification::query()->count())->toBe(1);
});

test('ppe classification names must be unique', function () {
    PpeClassification::factory()->create(['name' => 'Machinery and Equipment']);

    $this->actingAs(ppeUser(['ppe-classifications.create']))
        ->post(route('ppe-classifications.store'), [
            'code' => 'NEW',
            'name' => 'Machinery and Equipment',
        ])
        ->assertSessionHasErrors('name');

    expect(PpeClassification::query()->count())->toBe(1);
});

test('creating a ppe classification requires the create permission', function () {
    $this->actingAs(ppeUser())
        ->post(route('ppe-classifications.store'), [
            'code' => 'LAND',
            'name' => 'Land',
        ])
        ->assertForbidden();

    expect(PpeClassification::query()->count())->toBe(0);
});

test('a ppe classification can be updated', function () {
    $item = PpeClassification::factory()->create([
        'code' => 'OLD',
        'name' => 'Old Name',
        'description' => 'Old desc',
    ]);

    $this->actingAs(ppeUser(['ppe-classifications.update']))
        ->put(route('ppe-classifications.update', $item), [
            'code' => 'NEW',
            'name' => 'New Name',
            'description' => 'New desc',
            'is_active' => false,
        ])
        ->assertRedirect();

    $item->refresh();

    expect($item->code)->toBe('NEW')
        ->and($item->name)->toBe('New Name')
        ->and($item->description)->toBe('New desc')
        ->and($item->is_active)->toBeFalse();
});

test('a ppe classification may keep its own code and name when updated', function () {
    $item = PpeClassification::factory()->create([
        'code' => 'M&E',
        'name' => 'Machinery and Equipment',
    ]);

    $this->actingAs(ppeUser(['ppe-classifications.update']))
        ->put(route('ppe-classifications.update', $item), [
            'code' => 'M&E',
            'name' => 'Machinery and Equipment',
            'description' => 'Updated',
        ])
        ->assertSessionHasNoErrors();

    expect($item->refresh()->description)->toBe('Updated');
});

test('a ppe classification cannot take another code or name on update', function () {
    PpeClassification::factory()->create(['code' => 'M&E', 'name' => 'Machinery and Equipment']);
    $item = PpeClassification::factory()->create(['code' => 'TE', 'name' => 'Transportation Equipment']);

    $this->actingAs(ppeUser(['ppe-classifications.update']))
        ->put(route('ppe-classifications.update', $item), [
            'code' => 'M&E',
            'name' => 'Machinery and Equipment',
        ])
        ->assertSessionHasErrors(['code', 'name']);

    expect($item->refresh()->code)->toBe('TE');
});

test('updating a ppe classification requires the update permission', function () {
    $item = PpeClassification::factory()->create(['code' => 'M&E', 'name' => 'Machinery and Equipment']);

    $this->actingAs(ppeUser())
        ->put(route('ppe-classifications.update', $item), [
            'code' => 'CHANGED',
            'name' => 'Changed',
        ])
        ->assertForbidden();

    expect($item->refresh()->code)->toBe('M&E');
});

test('a ppe classification can be deleted', function () {
    $item = PpeClassification::factory()->create();

    $this->actingAs(ppeUser(['ppe-classifications.delete']))
        ->delete(route('ppe-classifications.destroy', $item))
        ->assertRedirect();

    $this->assertModelMissing($item);
});

test('deleting a ppe classification requires the delete permission', function () {
    $item = PpeClassification::factory()->create();

    $this->actingAs(ppeUser())
        ->delete(route('ppe-classifications.destroy', $item))
        ->assertForbidden();

    $this->assertModelExists($item);
});