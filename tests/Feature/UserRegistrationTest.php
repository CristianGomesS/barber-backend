<?php

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('registra um novo cliente via api', function () {
    // Garante que o Role exista pra esse teste passar (mesmo sem db reset)
    Role::firstOrCreate(['slug' => 'customer'], ['name' => 'Cliente']);

    $response = $this->postJson('/api/register', [
        'name' => 'Teste Pest',
        'email' => 'pest.teste@gmail.com',
        'password' => 'senha123',
        'password_confirmation' => 'senha123'
    ]);

    $response->assertStatus(201)
        ->assertJsonStructure(['token', 'message']);

    $this->assertDatabaseHas('users', [
        'email' => 'pest.teste@gmail.com'
    ]);
});

it('bloqueia emails descatáveis como emailq.com', function () {
    $response = $this->postJson('/api/register', [
        'name' => 'Zezinho Fake',
        'email' => 'fake@emailq.com',
        'password' => 'senha123',
        'password_confirmation' => 'senha123'
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

it('permite que administradores criem usuários com regra beforeStore', function () {
    // Criação do Admin
    $adminRole = Role::firstOrCreate(['slug' => 'admin'], ['name' => 'Admin']);
    $roleParaCriar = Role::firstOrCreate(['slug' => 'employee'], ['name' => 'Employee Test']);

    $admin = User::factory()->create(['role_id' => $adminRole->id]);

    $response = $this->actingAs($admin)->postJson('/api/users', [
        'name' => 'Novo Barbeiro',
        'email' => 'newbarber@barbearia.com',
        'password' => 'senha123',
        'role_id' => $roleParaCriar->id
    ]);

    $response->assertStatus(201);
    $this->assertDatabaseHas('users', ['email' => 'newbarber@barbearia.com']);
});