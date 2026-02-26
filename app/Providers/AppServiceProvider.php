<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Por enquanto, não conseguimos fazer o bind de uma classe abstrata diretamente, 
        // mas o Laravel fará isso automaticamente quando criarmos os repositórios reais.

        // O que você vai registrar aqui são os CONTRATOS REAIS que o sistema vai usar.
        // Exemplo:
        // $this->app->bind(ServiceRepositoryInterface::class, ServiceRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        
    }
}
