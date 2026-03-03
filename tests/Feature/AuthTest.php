<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('autentica o usuário e retorna o token', function () {
    $user = User::factory()->create([
        'password' => $password = 'senha-super-secreta',
    ]);

    $response = $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => $password,
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure(['token', 'message']);
});

it('bloqueia o login com senha errada', function () {
    $user = User::factory()->create([
        'password' => bcrypt('senha-correta'),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => 'senha-errada-aqui',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});
