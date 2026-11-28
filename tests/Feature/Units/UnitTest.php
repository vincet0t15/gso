<?php

use App\Models\Unit;
use Spatie\Permission\Models\Permission;
use App\Models\User;

function unitUser(array $permissions = ['units.view']): User
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
    $response = $this->get(route('units.index'));

    $response->assertRedirect(route('login'));
});

test('accounts without the administration gate cannot list units', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('units.index'))
        ->assertForbidden();
});

test('accounts with the view permission can list units', function () {
    Unit::factory()->create(['code' => 'pcs', 'name' => 'Pieces']);

    $this->actingAs(unitUser())
        ->get(route('units.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('units/index')
            ->has('units.data', 1)
            ->where('units.data.0.code', 'pcs')
        );
});

test('units can be searched by code, name or description', function () {
    Unit::factory()->create([
        'code' => 'pcs',
        'name' => 'Pieces',
        'description' => 'Individual pieces',
    ]);
    Unit::factory()->create([
        'code' => 'box',
        'name' => 'Box',
        'description' => 'Boxed items',
    ]);

    $this->actingAs(unitUser())
        ->get(route('units.index', ['search' => 'Boxed']))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->has('units.data', 1)
            ->where('units.data.0.code', 'box')
        );
});

test('a unit can be created', function () {
    $this->actingAs(unitUser(['units.create']))
        ->post(route('units.store'), [
            'code' => 'ream',
            'name' => 'Ream',
            'description' => '500 sheets',
            'is_active' => true,
        ])
        ->assertRedirect();

    $item = Unit::query()->where('code', 'ream')->sole();

    expect($item->name)->toBe('Ream')
        ->and($item->is_active)->toBeTrue();
});

test('creating a unit requires code and name', function () {
    $this->actingAs(unitUser(['units.create']))
        ->post(route('units.store'), [
            'code' => '',
            'name' => '',
        ])
        ->assertSessionHasErrors(['code', 'name']);

    expect(Unit::query()->count())->toBe(0);
});

test('unit codes must be unique', function () {
    Unit::factory()->create(['code' => 'pcs']);

    $this->actingAs(unitUser(['units.create']))
        ->post(route('units.store'), [
            'code' => 'pcs',
            'name' => 'Another',
        ])
        ->assertSessionHasErrors('code');

    expect(Unit::query()->count())->toBe(1);
});

test('unit names must be unique', function () {
    Unit::factory()->create(['name' => 'Pieces']);

    $this->actingAs(unitUser(['units.create']))
        ->post(route('units.store'), [
            'code' => 'NEW',
            'name' => 'Pieces',
        ])
        ->assertSessionHasErrors('name');

    expect(Unit::query()->count())->toBe(1);
});

test('creating a unit requires the create permission', function () {
    $this->actingAs(unitUser())
        ->post(route('units.store'), [
            'code' => 'ream',
            'name' => 'Ream',
        ])
        ->assertForbidden();

    expect(Unit::query()->count())->toBe(0);
});

test('a unit can be updated', function () {
    $item = Unit::factory()->create([
        'code' => 'OLD',
        'name' => 'Old Name',
        'description' => 'Old desc',
    ]);

    $this->actingAs(unitUser(['units.update']))
        ->put(route('units.update', $item), [
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

test('a unit may keep its own code and name when updated', function () {
    $item = Unit::factory()->create([
        'code' => 'pcs',
        'name' => 'Pieces',
    ]);

    $this->actingAs(unitUser(['units.update']))
        ->put(route('units.update', $item), [
            'code' => 'pcs',
            'name' => 'Pieces',
            'description' => 'Updated',
        ])
        ->assertSessionHasNoErrors();

    expect($item->refresh()->description)->toBe('Updated');
});

test('a unit cannot take another code or name on update', function () {
    Unit::factory()->create(['code' => 'pcs', 'name' => 'Pieces']);
    $item = Unit::factory()->create(['code' => 'box', 'name' => 'Box']);

    $this->actingAs(unitUser(['units.update']))
        ->put(route('units.update', $item), [
            'code' => 'pcs',
            'name' => 'Pieces',
        ])
        ->assertSessionHasErrors(['code', 'name']);

    expect($item->refresh()->code)->toBe('box');
});

test('updating a unit requires the update permission', function () {
    $item = Unit::factory()->create(['code' => 'pcs', 'name' => 'Pieces']);

    $this->actingAs(unitUser())
        ->put(route('units.update', $item), [
            'code' => 'CHANGED',
            'name' => 'Changed',
        ])
        ->assertForbidden();

    expect($item->refresh()->code)->toBe('pcs');
});

test('a unit can be deleted', function () {
    $item = Unit::factory()->create();

    $this->actingAs(unitUser(['units.delete']))
        ->delete(route('units.destroy', $item))
        ->assertRedirect();

    $this->assertModelMissing($item);
});

test('deleting a unit requires the delete permission', function () {
    $item = Unit::factory()->create();

    $this->actingAs(unitUser())
        ->delete(route('units.destroy', $item))
        ->assertForbidden();

    $this->assertModelExists($item);
});