<?php

use App\Models\Type;
use Spatie\Permission\Models\Permission;
use App\Models\User;

function typeUser(array $permissions = ['types.view']): User
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
    $response = $this->get(route('types.index'));

    $response->assertRedirect(route('login'));
});

test('accounts without the administration gate cannot list types', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('types.index'))
        ->assertForbidden();
});

test('accounts with the view permission can list types', function () {
    Type::factory()->create(['name' => 'Office Supplies']);

    $this->actingAs(typeUser())
        ->get(route('types.index'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('types/index')
            ->has('types.data', 1)
            ->where('types.data.0.name', 'Office Supplies')
        );
});

test('types can be searched by name or description', function () {
    Type::factory()->create([
        'name' => 'Office Supplies',
        'description' => 'Pens and paper',
    ]);
    Type::factory()->create([
        'name' => 'Equipment',
        'description' => 'Machines',
    ]);

    $this->actingAs(typeUser())
        ->get(route('types.index', ['search' => 'Machines']))
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->has('types.data', 1)
            ->where('types.data.0.name', 'Equipment')
        );
});

test('a type can be created', function () {
    $this->actingAs(typeUser(['types.create']))
        ->post(route('types.store'), [
            'name' => 'Cleaning Materials',
            'description' => 'Brooms and mops',
            'is_active' => true,
        ])
        ->assertRedirect();

    $type = Type::query()->where('name', 'Cleaning Materials')->sole();

    expect($type->description)->toBe('Brooms and mops')
        ->and($type->is_active)->toBeTrue();
});

test('creating a type requires a name', function () {
    $this->actingAs(typeUser(['types.create']))
        ->post(route('types.store'), [
            'name' => '',
        ])
        ->assertSessionHasErrors('name');

    expect(Type::query()->count())->toBe(0);
});

test('type names must be unique', function () {
    Type::factory()->create(['name' => 'Office Supplies']);

    $this->actingAs(typeUser(['types.create']))
        ->post(route('types.store'), [
            'name' => 'Office Supplies',
        ])
        ->assertSessionHasErrors('name');

    expect(Type::query()->count())->toBe(1);
});

test('creating a type requires the create permission', function () {
    $this->actingAs(typeUser())
        ->post(route('types.store'), [
            'name' => 'Office Supplies',
        ])
        ->assertForbidden();

    expect(Type::query()->count())->toBe(0);
});

test('a type can be updated', function () {
    $type = Type::factory()->create([
        'name' => 'Old Name',
        'description' => 'Old desc',
    ]);

    $this->actingAs(typeUser(['types.update']))
        ->put(route('types.update', $type), [
            'name' => 'New Name',
            'description' => 'New desc',
            'is_active' => false,
        ])
        ->assertRedirect();

    $type->refresh();

    expect($type->name)->toBe('New Name')
        ->and($type->description)->toBe('New desc')
        ->and($type->is_active)->toBeFalse();
});

test('a type may keep its own name when updated', function () {
    $type = Type::factory()->create([
        'name' => 'Office Supplies',
        'description' => 'BO',
    ]);

    $this->actingAs(typeUser(['types.update']))
        ->put(route('types.update', $type), [
            'name' => 'Office Supplies',
            'description' => 'BUD',
        ])
        ->assertSessionHasNoErrors();

    expect($type->refresh()->description)->toBe('BUD');
});

test('a type cannot take another type name on update', function () {
    Type::factory()->create(['name' => 'Office Supplies']);
    $type = Type::factory()->create(['name' => 'Equipment']);

    $this->actingAs(typeUser(['types.update']))
        ->put(route('types.update', $type), [
            'name' => 'Office Supplies',
        ])
        ->assertSessionHasErrors('name');

    expect($type->refresh()->name)->toBe('Equipment');
});

test('updating a type requires the update permission', function () {
    $type = Type::factory()->create(['name' => 'Equipment']);

    $this->actingAs(typeUser())
        ->put(route('types.update', $type), [
            'name' => 'Changed',
        ])
        ->assertForbidden();

    expect($type->refresh()->name)->toBe('Equipment');
});

test('a type can be deleted', function () {
    $type = Type::factory()->create();

    $this->actingAs(typeUser(['types.delete']))
        ->delete(route('types.destroy', $type))
        ->assertRedirect();

    $this->assertModelMissing($type);
});

test('deleting a type requires the delete permission', function () {
    $type = Type::factory()->create();

    $this->actingAs(typeUser())
        ->delete(route('types.destroy', $type))
        ->assertForbidden();

    $this->assertModelExists($type);
});