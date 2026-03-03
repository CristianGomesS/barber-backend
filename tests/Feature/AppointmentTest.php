<?php

use App\Models\User;
use App\Models\Service;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

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

it('permite que barbeiro ou admin finalize seu atendimento', function () {
    $roleEmployee = \App\Models\Role::firstOrCreate(['slug' => 'employee'], ['name' => 'Funcionário/Barbeiro']);
    $barbeiro = User::factory()->create(['role_id' => $roleEmployee->id]);

    $appointment = Appointment::create([
        'customer_id' => User::factory()->create()->id,
        'employee_id' => $barbeiro->id,
        'service_id' => Service::firstOrCreate(['name' => 'Corte Teste'], ['duration_minutes' => 30])->id,
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