<?php

use App\Models\User;
use App\Models\Service;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

it('retorna os agendamentos do usuario logado', function () {
    $cliente = User::factory()->create();
    $barbeiro = User::factory()->create();
    $servico = Service::firstOrCreate(['name' => 'Corte Teste'], ['duration_minutes' => 30]);

    // O barbeiro precisa estar vinculado ao serviço
    $barbeiro->services()->attach($servico->id, ['price' => 50.0]);

    // Criando um agendamento direto pelo factory
    Appointment::factory()->create([
        'customer_id' => $cliente->id,
        'employee_id' => $barbeiro->id,
        'service_id' => $servico->id,
        'scheduled_at' => Carbon::now()->addDay()->format('Y-m-d H:i:s'),
        'end_at' => Carbon::now()->addDay()->addMinutes(30)->format('Y-m-d H:i:s'),
        'final_price' => 50.0,
        'status' => 'pending'
    ]);

    $response = $this->actingAs($cliente)->getJson('/api/appointments');

    $response->assertStatus(200)
        ->assertJsonCount(1);
});

it('bloqueia agendamento em datas passadas', function () {
    $cliente = User::factory()->create();
    $barbeiro = User::factory()->create();
    $servico = Service::firstOrCreate(['name' => 'Corte Teste'], ['duration_minutes' => 30]);

    // O barbeiro precisa estar vinculado ao serviço
    $barbeiro->services()->attach($servico->id, ['price' => 50.0]);

    $response = $this->actingAs($cliente)->postJson('/api/appointments', [
        'employee_id' => $barbeiro->id,
        'service_id' => $servico->id,
        'scheduled_at' => Carbon::now()->subDay()->format('Y-m-d H:i:s')
    ]);

    $response->assertStatus(422)
        ->assertJsonFragment([
            'error' => 'Erro no envio de dados.'
        ]);
});

it('cria um agendamento com sucesso', function () {
    $roleEmployee = \App\Models\Role::firstOrCreate(['slug' => 'employee'], ['name' => 'Funcionário/Barbeiro']);
    $barbeiro = User::factory()->create(['role_id' => $roleEmployee->id]);
    $cliente = User::factory()->create();
    $servico = Service::factory()->create(['duration_minutes' => 30]);

    // O barbeiro precisa estar vinculado ao serviço
    $barbeiro->services()->attach($servico->id, ['price' => 50.0]);

    $scheduledAt = Carbon::now()->addDay()->format('Y-m-d H:i:s');

    $response = $this->actingAs($cliente)->postJson('/api/appointments', [
        'employee_id' => $barbeiro->id,
        'service_id' => $servico->id,
        'scheduled_at' => $scheduledAt
    ]);

    $response->assertStatus(201)
        ->assertJsonFragment([
            'message' => 'Agendamento solicitado com sucesso!'
        ]);

    $this->assertDatabaseHas('appointments', [
        'customer_id' => $cliente->id,
        'employee_id' => $barbeiro->id,
        'service_id' => $servico->id,
        'status' => 'pending'
    ]);
});

it('retorna a agenda diaria do barbeiro', function () {
    $roleEmployee = \App\Models\Role::firstOrCreate(['slug' => 'employee'], ['name' => 'Barbeiro']);
    $barbeiro = User::factory()->create(['role_id' => $roleEmployee->id]);
    $cliente = User::factory()->create();
    $servico = Service::factory()->create(['duration_minutes' => 30]);

    $barbeiro->services()->attach($servico->id, ['price' => 50.0]);

    $appointment = Appointment::factory()->create([
        'customer_id' => $cliente->id,
        'employee_id' => $barbeiro->id,
        'service_id' => $servico->id,
        'scheduled_at' => Carbon::now()->format('Y-m-d 10:00:00'),
        'end_at' => Carbon::now()->format('Y-m-d 10:30:00'),
        'final_price' => 50.0,
        'status' => 'pending'
    ]);

    $response = $this->actingAs($barbeiro)->getJson('/api/appointments/mySchedule');

    $response->assertStatus(200)
        ->assertJsonCount(1);
});

it('permite que barbeiro ou admin finalize seu atendimento', function () {
    $roleEmployee = \App\Models\Role::firstOrCreate(['slug' => 'employee'], ['name' => 'Funcionário/Barbeiro']);
    $barbeiro = User::factory()->create(['role_id' => $roleEmployee->id]);
    $servico = Service::factory()->create();

    $barbeiro->services()->attach($servico->id, ['price' => 50.0]);

    $appointment = Appointment::factory()->create([
        'customer_id' => User::factory()->create()->id,
        'employee_id' => $barbeiro->id,
        'service_id' => $servico->id,
        'scheduled_at' => Carbon::now()->addDay()->format('Y-m-d H:i:s'),
        'end_at' => Carbon::now()->addDay()->addMinutes(30)->format('Y-m-d H:i:s'),
        'final_price' => 50.0,
        'status' => 'pending'
    ]);

    $response = $this->actingAs($barbeiro)->putJson("/api/appointments/{$appointment->id}/finalize");

    $response->assertStatus(200);
    $this->assertDatabaseHas('appointments', [
        'id' => $appointment->id,
        'status' => 'completed'
    ]);
});