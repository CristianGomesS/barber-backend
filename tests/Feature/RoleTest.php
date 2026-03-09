<?php

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

beforeEach(function () {
    // Criamos a role admin se não existir e um usuário administrador para os testes
    $this->adminRole = Role::firstOrCreate(['slug' => 'admin'], ['name' => 'Administrador']);
    $this->adminUser = User::factory()->create(['role_id' => $this->adminRole->id]);
});

it('deve listar todos os cargos para um administrador', function () {
    Role::factory()->count(3)->create();

    $response = $this->actingAs($this->adminUser)
        ->getJson('/api/roles');

    $response->assertStatus(200)
        ->assertJsonCount(Role::count()); // Verifica se trouxe todos
});

it('deve criar um novo cargo com sucesso', function () {
    $roleData = [
        'name' => 'Barbeiro Sênior',
        'slug' => 'barbeiro-senior'
    ];

    $response = $this->actingAs($this->adminUser)
        ->postJson('/api/roles', $roleData);

    $response->assertStatus(201);
    $this->assertDatabaseHas('roles', $roleData);
});

//ainda nao funciona
it('deve exibir um cargo específico por ID', function () {
    $role = Role::factory()->create(['name' => 'Recepcionista']);

    $response = $this->actingAs($this->adminUser)
        ->getJson("/api/roles/{$role->id}");

    $response->assertStatus(200)
        ->assertJsonPath('data.name', 'Recepcionista');
});

it('deve atualizar um cargo existente', function () {
    $role = Role::factory()->create(['name' => 'Antigo Nome']);

    $response = $this->actingAs($this->adminUser)
        ->putJson("/api/roles/{$role->id}", [
            'name' => 'Nome Atualizado',
            'slug' => 'nome-atualizado'
        ]);

    $response->assertStatus(200);
    $this->assertDatabaseHas('roles', ['name' => 'Nome Atualizado']);
});

it('deve remover (soft delete) um cargo', function () {
    $role = Role::factory()->create();

    $response = $this->actingAs($this->adminUser)
        ->deleteJson("/api/roles/{$role->id}");

    $response->assertStatus(204); // Ou 200, dependendo do seu controller
    $this->assertSoftDeleted('roles', ['id' => $role->id]);
});
// ainda nao funciona
it('deve restaurar um cargo deletado', function () {
    $role = Role::factory()->create();
    $role->delete();

    $response = $this->actingAs($this->adminUser)
        ->putJson("/api/roles/restore/{$role->id}");

    $response->assertStatus(204);
    $this->assertNotSoftDeleted('roles', ['id' => $role->id]);
});