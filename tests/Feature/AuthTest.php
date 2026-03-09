<?php

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Mail\Mailable;

uses(DatabaseTransactions::class);

it('autentica o usuário e retorna o token', function () {
    $role = \App\Models\Role::firstOrCreate(['slug' => 'customer'], ['name' => 'Cliente']);
    $user = User::factory()->create([
        'password' => $password = 'senha-super-secreta',
        'role_id' => $role->id,
    ]);

    $response = $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => $password,
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure(['token', 'message']);
});

it('bloqueia o login com senha errada', function () {
    $role = \App\Models\Role::firstOrCreate(['slug' => 'customer'], ['name' => 'Cliente']);
    $user = User::factory()->create([
        'password' => bcrypt('senha-correta'),
        'role_id' => $role->id,
    ]);

    $response = $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => 'senha-errada-aqui',
    ]);

    $response->assertStatus(422)
        ->assertJson([
            'message' => 'As credenciais fornecidas estão incorretas.',
            'errors' => [
                'email' => ['As credenciais fornecidas estão incorretas.']
            ]
        ]);
});

it('deve deslogar o usuário invalidando o token atual', function () {
    $role = \App\Models\Role::firstOrCreate(['slug' => 'customer'], ['name' => 'Cliente']);
    $user = User::factory()->create(['role_id' => $role->id]);

    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withHeader('Authorization', "Bearer $token")
        ->postJson('/api/auth/logout');

    $response->assertStatus(204);

    $this->assertDatabaseMissing('personal_access_tokens', [
        'tokenable_id' => $user->id,
        'name' => 'test-token'
    ]);
});

it('não permite logout para usuários não autenticados', function () {
    $response = $this->postJson('/api/auth/logout');

    $response->assertStatus(401);
});

it('gera um token de recuperação e envia o e-mail para um usuário existente', function () {
    Mail::fake();

    $role = \App\Models\Role::firstOrCreate(['slug' => 'customer'], ['name' => 'Cliente']);
    $user = User::factory()->create([
        'email' => 'cliente@barbershop.com',
        'role_id' => $role->id
    ]);

    $response = $this->postJson('/api/forget-password', [
        'email' => 'cliente@barbershop.com'
    ]);

    $response->assertStatus(200);
});

it('retorna erro ao tentar recuperar senha de um e-mail inexistente', function () {
    $response = $this->postJson('/api/forget-password', [
        'email' => 'nao-existo@gmail.com'
    ]);

    $response->assertStatus(422)
        ->assertJson(['error' => 'Erro no envio de dados.']);
});

it('valida que um token de recuperação é válido', function () {
    $user = User::factory()->create([
        'email' => 'cliente@barbershop.com',
    ]);
    DB::table('password_reset_tokens')->insert([
        'email' => $user->email,
        'token' => '12345',
        'created_at' => now()
    ]);

    $response = $this->postJson('/api/valid-token', [
        'email' => 'cliente@barbershop.com',
        'token' => '12345'
    ]);

    $response->assertStatus(200)
        ->assertJson(['message' => 'O seu código é válido!']);
});

it('altera a senha do usuário com um token válido', function () {
    $token = '54321';
    $newPassword = 'nova-senha-123';

    $role = \App\Models\Role::firstOrCreate(['slug' => 'employee'], ['name' => 'Funcionário']);
    $user = User::factory()->create([
        'email' => 'barbeiro@teste.com',
        'role_id' => $role->id,
        'password' => bcrypt('senha-antiga')
    ]);

    DB::table('password_reset_tokens')->insert([
        'email' => $user->email,
        'token' => $token,
        'created_at' => now()
    ]);

    $response = $this->postJson('/api/reset-password', [
        'email' => $user->email,
        'token' => $token,
        'password' => $newPassword,
        'password_confirmation' => $newPassword
    ]);

    $user->refresh();

    $response->assertStatus(200)
        ->assertJson(['message' => 'Senha alterada com sucesso!']);
});