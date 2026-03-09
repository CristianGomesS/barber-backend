<?php

use App\Models\User;
use App\Models\Service;
use App\Models\EmployeeAvailability;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

it('salva a disponibilidade em lote para um barbeiro', function () {
    $roleEmployee = \App\Models\Role::firstOrCreate(['slug' => 'employee'], ['name' => 'Barbeiro']);
    $barbeiro = User::factory()->create(['role_id' => $roleEmployee->id]);

    $response = $this->actingAs($barbeiro)->postJson('/api/barbers/availability', [
        'user_id' => $barbeiro->id,
        'availabilities' => [
            [
                'day_of_week' => 1, // Segunda-feira
                'start_time' => '09:00',
                'end_time' => '18:00',
                'break_start' => '12:00',
                'break_end' => '13:00',
                'is_active' => true
            ]
        ]
    ]);

    $response->assertStatus(200)
        ->assertJsonFragment([
            'message' => 'Disponibilidade salva com sucesso!'
        ]);

    $this->assertDatabaseHas('employee_availabilities', [
        'user_id' => $barbeiro->id,
        'day_of_week' => 1,
        'start_time' => '09:00',
        'end_time' => '18:00',
    ]);
});

it('retorna os horarios disponiveis de um barbeiro', function () {
    $roleEmployee = \App\Models\Role::firstOrCreate(['slug' => 'employee'], ['name' => 'Barbeiro']);
    $barbeiro = User::factory()->create(['role_id' => $roleEmployee->id]);

    // O próximo segunda
    $nextMonday = Carbon::now()->next(Carbon::MONDAY)->format('Y-m-d');

    // Vincula o serviço pra ele conseguir passar pela validação de tempo, mas a controller de slots só precisa de service_id se filtrar
    $servico = Service::factory()->create(['duration_minutes' => 30]);
    $barbeiro->services()->attach($servico->id, ['price' => 50.0]);

    // Cadastra a disponibilidade pro proximo segunda
    EmployeeAvailability::create([
        'user_id' => $barbeiro->id,
        'day_of_week' => Carbon::MONDAY,
        'start_time' => '09:00',
        'end_time' => '12:00',
        'break_start' => null,
        'break_end' => null,
        'is_active' => 1
    ]);

    // O cliente vai buscar slots
    $cliente = User::factory()->create();

    $response = $this->actingAs($cliente)->getJson("/api/barbers/{$barbeiro->id}/slots?date={$nextMonday}&service_id={$servico->id}");

    $response->assertStatus(200);
    $response->assertJsonIsArray('data');
});
